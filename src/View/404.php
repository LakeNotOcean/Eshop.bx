<?php

$random = random_int(0, 1);

$tetris = $random === 0;
$particles = $random === 1;

?>

<link rel="stylesheet" href="/css/404.css">

<div class="container"></div>

<script>
	let backgroundColor = getComputedStyle(document.body).getPropertyValue('--color-background');
</script>

<?php if ($tetris):?>
	<script>
		//region classes
		class Square
		{
			color;
			size;
			constructor(x,y)
			{
				this.x = x;
				this.y = y;
			}
			equals(another)
			{
				return (this.x === another.x && this.y === another.y);
			}
			draw()
			{
				fill(this.color);
				rect(this.x * this.size, this.y * this.size, this.size, this.size)
			}
		}

		class Piece
		{
			colors = ['blueviolet', 'aqua', 'blue', 'orange', 'gold', 'chartreuse', 'red'];
			types = [
				[new Square(6,0), new Square(5,1), new Square(6,1), new Square(7,1)], //T shape
				[new Square(4,0), new Square(5,0), new Square(6,0), new Square(7,0)], //line shape
				[new Square(5,0), new Square(5,1), new Square(6,1), new Square(7,1)], //inverse L shape
				[new Square(7,0), new Square(5,1), new Square(6,1), new Square(7,1)], //L shape
				[new Square(6,0), new Square(7,0), new Square(6,1), new Square(7,1)], //square shape
				[new Square(6,0), new Square(7,0), new Square(5,1), new Square(6,1)], //Z shape
				[new Square(5,0), new Square(6,0), new Square(6,1), new Square(7,1)]  //inverse Z shape
			];
			squares = [];
			size;

			constructor(pieceSize)
			{
				this.size = pieceSize;
				let rand = Math.floor(Math.random() * this.colors.length)
				this.color = this.colors[rand];

				let squares = this.types[rand];
				let n = squares.length;
				for (let i = 0; i < n; i++)
				{
					squares[i].size = this.size;
					this.squares.push(squares[i]);
				}
			}

			copy(piece)
			{
				this.size = piece.size;
				this.color = 'rgba(0,0,0,0.3)';
				this.squares = [];
				for (let i = 0; i < piece.squares.length; i++) {
					let square = new Square(piece.squares[i].x, piece.squares[i].y)
					square.size = piece.size;
					this.squares[i] = square;
				}
			}

			draw()
			{
				fill(this.color);
				stroke('white');
				for (let i = 0; i < this.squares.length; i++)
				{
					let square = this.squares[i];
					rect(square.x * this.size, square.y * this.size, this.size, this.size)
				}
			}

			moveDown()
			{
				for (let i = 0; i < this.squares.length; i++)
				{
					this.squares[i].y += 1;
				}
			}

			moveUp()
			{
				for (let i = 0; i < this.squares.length; i++)
				{
					this.squares[i].y -= 1;
				}
			}

			moveLeft()
			{
				for (let i = 0; i < this.squares.length; i++)
				{
					this.squares[i].x -= 1;
				}
			}

			moveRight()
			{
				for (let i = 0; i < this.squares.length; i++)
				{
					this.squares[i].x += 1;
				}
			}

			rotate()
			{
				let cx = 0;
				let cy = 0;
				for (let i = 0; i < this.squares.length; i++)
				{
					cx += this.squares[i].x;
					cy += this.squares[i].y;
				}
				cx = Math.round(cx/this.squares.length);
				cy = Math.round(cy/this.squares.length);
				for (let i = 0; i < this.squares.length; i++)
				{
					this.squares[i].x -= cx;
					this.squares[i].y -= cy;
					let x = - this.squares[i].y;
					let y = this.squares[i].x;
					this.squares[i].x = x + cx;
					this.squares[i].y = y + cy;
				}
			}

			left()
			{
				let l = this.squares[0].x;
				for (let i = 1; i < this.squares.length; i++)
				{
					if (this.squares[i].x < l)
					{
						l = this.squares[i].x;
					}
				}
				return l;
			}

			right()
			{
				let r = this.squares[0].x;
				for (let i = 1; i < this.squares.length; i++)
				{
					if (this.squares[i].x > r)
					{
						r = this.squares[i].x;
					}
				}
				return r;
			}

			bottom()
			{
				let b = this.squares[0].y;
				for (let i = 1; i < this.squares.length; i++)
				{
					if (this.squares[i].y > b)
					{
						b = this.squares[i].y;
					}
				}
				return b;
			}

		}
		//endregion classes

		//region init

		let canvas = document.createElement('canvas');
		let container = document.querySelector('.container');

		canvas.height = container.getBoundingClientRect().height;
		canvas.width = canvas.height / 1.5;

		let rowLength = 20;
		let pieceSize = canvas.width/rowLength;

		container.append(canvas);

		let ctx = canvas.getContext('2d');
		//endregion init
		let columnHeight = Math.floor(canvas.height/pieceSize);

		let squares = [];
		let currentPiece = new Piece(pieceSize);

		document.addEventListener('keydown', function(event) {
			if (event.code === 'ArrowUp')
			{
				let copy = new Piece();
				copy.copy(currentPiece);
				copy.rotate();
				if (!collides(copy))
				{
					currentPiece.rotate()
				}
				while (currentPiece.left() < 0)
				{
					currentPiece.moveRight();
				}
				while (currentPiece.right() >= rowLength)
				{
					currentPiece.moveLeft();
				}
				draw();
			}
			if (event.code === 'ArrowLeft')
			{
				moveLeft();
				draw();
			}
			if (event.code === 'ArrowRight' && currentPiece.right() < rowLength - 1)
			{
				moveRight();
				draw();
			}
			if (event.code === 'ArrowDown')
			{
				let projection = getProjection();
				let color = currentPiece.color;
				currentPiece.copy(projection);
				currentPiece.color = color;
				moveDown();
				draw();
			}
		});

		function update() {
			draw();
			moveDown();
		}

		function draw()
		{
			background(backgroundColor);

			fill('black');
			stroke('white');
			for (let i = 0; i < squares.length; i++)
			{
				squares[i].draw();
			}
			currentPiece.draw();
			project()
		}

		function moveDown()
		{
			if (currentPiece.bottom() >= columnHeight - 1)
			{
				spawnPiece()
				return;
			}
			if (currentPiece.bottom() < columnHeight - 1)
			{
				currentPiece.moveDown();
			}
			if (collides(currentPiece))
			{
				currentPiece.moveUp();
				spawnPiece()
			}
		}

		function moveLeft()
		{
			if (currentPiece.left() > 0)
			{
				currentPiece.moveLeft();
			}
			if (collides(currentPiece))
			{
				currentPiece.moveRight();
			}
		}

		function moveRight()
		{
			if (currentPiece.right() < rowLength - 1)
			{
				currentPiece.moveRight();
			}
			if (collides(currentPiece))
			{
				currentPiece.moveLeft();
			}
		}

		function spawnPiece()
		{
			for (let i = 0; i < currentPiece.squares.length; i++)
			{
				currentPiece.squares[i].color = currentPiece.color;
				squares.push(currentPiece.squares[i]);
			}
			removeRow();
			currentPiece = new Piece(pieceSize);
			draw();
		}

		function project()
		{
			let projection = getProjection();
			projection.draw();
		}

		function getProjection()
		{
			let projection = new Piece();
			projection.copy(currentPiece);
			while (projection.bottom() < columnHeight - 1 && !collides(projection))
			{
				projection.moveDown();
			}
			if (collides(projection))
			{
				projection.moveUp();
			}
			return projection;
		}

		function collides(piece)
		{
			for (let i = 0; i < squares.length; i++)
			{
				for (let j = 0; j < piece.squares.length; j++)
				{
					if (piece.squares[j].equals(squares[i]))
					{
						return true;
					}
				}
			}
			return false;
		}

		function removeRow()
		{
			let matrix = Array.from(Array(columnHeight), () => new Array(rowLength));
			for (let i = 0; i < squares.length; i++)
			{
				matrix[squares[i].y][squares[i].x] = true;
			}
			let row = findRow(matrix);
			if (!row)
			{
				return;
			}
			for (let i = 0; i < squares.length; i++)
			{
				if (squares[i].y === row)
				{
					squares.splice(i, 1);
					i--;
				}
			}
			for (let i = 0; i < squares.length; i++)
			{
				if (squares[i].y < row)
				{
					squares[i].y++;
				}
			}
			removeRow();
		}

		function findRow(matrix)
		{
			for(let y = 0; y < matrix.length; y++)
			{
				let full = true;
				for(let x = 0; x < matrix[y].length; x++)
				{
					if (!matrix[y][x])
					{
						full = false;
						break;
					}
				}
				if (full)
				{
					return y;
				}
			}
			return false;
		}

		//region helper functions
		function background(color)
		{
			fill(color);
			ctx.fillRect(0, 0, canvas.width, canvas.height);
		}

		function fill(color)
		{
			ctx.fillStyle = color;
		}

		function stroke(color)
		{
			ctx.strokeStyle = color;
		}

		function rect(x,y,w,h)
		{
			ctx.fillRect(x, y, w, h);
			ctx.strokeRect(x, y, w, h);
		}

		update()
		setInterval(update, 500);
		//endregion helper functions

		window.addEventListener('resize', () => {
			let oldHeight = canvas.height;
			let oldWidth = canvas.width;
			canvas.height = container.getBoundingClientRect().height;
			canvas.width = canvas.height/1.5;
			ctx.scale(canvas.height/oldHeight, canvas.width/oldWidth);
		});
		window.addEventListener("keydown", function(e) {
			if(["Space","ArrowUp","ArrowDown","ArrowLeft","ArrowRight"].indexOf(e.code) > -1) {
				e.preventDefault();
			}
		}, false);
	</script>
<?php endif;?>

<?php if ($particles):?>
	<script>
	//region init
	let fontColor = getComputedStyle(document.body).getPropertyValue('--color-font');
	let mouseX = 0;
	let mouseY = 0;
	let canvas = document.createElement('canvas');
	let container = document.querySelector('.container');
	canvas.height = container.getBoundingClientRect().height;
	canvas.width = container.getBoundingClientRect().width;
	container.append(canvas);
	let ctx = canvas.getContext('2d');
	//endregion
	class Vector {
		constructor(x = 0, y = 0) {
			this.x = x
			this.y = y;
		}

		static vecAdd(vector1, vector2) {
			return new Vector(vector1.x + vector2.x, vector1.y + vector2.y)
		}
		add(vector) {
			this.x += vector.x;
			this.y += vector.y;
		}

		static vecSub(vector1, vector2) {
			return new Vector(vector1.x - vector2.x, vector1.y - vector2.y)
		}
		sub(vector) {
			this.x += vector.x;
			this.y += vector.y;
		}

		mult(lambda) {
			this.x *= lambda;
			this.y *= lambda;
		}
		mag() {
			return Math.sqrt(this.x * this.x + this.y * this.y);
		}
		setMag(newMag) {
			let curMag = this.mag();
			this.x *= newMag / curMag;
			this.y *= newMag / curMag;
		}
		limit(maxMag) {
			if (this.mag() > maxMag) {
				this.setMag(maxMag)
			}
		}
	}

	class Particle {
		constructor(x, y)
		{
			this.pos = new Vector(Math.random() * canvas.width, Math.random() * canvas.height);
			this.target = new Vector(x, y);
			this.vel = new Vector();
			this.acc = new Vector();
			this.r = 8;
			this.maxspeed = 15;
			this.maxforce = 1.5;
		}

		behaviors() {
			let arrive = this.arrive(this.target);
			let mouse = new Vector(mouseX, mouseY);
			let flee = this.flee(mouse);

			arrive.mult(1);
			flee.mult(5);

			this.applyForce(arrive);
			this.applyForce(flee);
		}

		applyForce(f) {
			this.acc.add(f);
		}

		update() {
			this.pos.add(this.vel);
			this.vel.add(this.acc);
			this.acc.mult(0);
		}

		show() {
			fill(fontColor);
			circle(this.pos.x, this.pos.y, this.r);
		}

		arrive(target) {
			let desired = Vector.vecSub(target, this.pos);
			let d = desired.mag();
			let speed = this.maxspeed;
			if (d < 100) {
				speed = map(d, 0, 100, 0, this.maxspeed);
			}
			desired.setMag(speed);
			let steer = Vector.vecSub(desired, this.vel);
			steer.limit(this.maxforce);
			return steer;
		}

		flee(target) {
			let desired = Vector.vecSub(target, this.pos);
			let d = desired.mag();
			if (d < 50) {
				desired.setMag(this.maxspeed);
				desired.mult(-1);
				let steer = Vector.vecSub(desired, this.vel);
				steer.limit(this.maxforce);
				return steer;
			} else {
				return new Vector();
			}
		}
	}

	let particles;

	function setup() {
		let w = canvas.width;
		let h = canvas.height;
		let i, radius = 10, data32;
		particles = [];
		ctx.clearRect(0, 0, w, h);
		ctx.font = "15px Montserrat";
		ctx.textBaseline = 'middle';
		ctx.textAlign = "center";
		ctx.fillText("4 0 4".toUpperCase(), w/40, h/40);
		data32 = new Uint32Array(ctx.getImageData(0, 0, w, h).data.buffer);
		for(i = 0; i < data32.length; i++) {
			if (data32[i] & 0xff000000) {
				let x = (i % w) * radius * 2 + radius;
				let y = ((i / w)|0) * radius * 2 + radius;
				particles.push(new Particle(x, y));
			}
		}
	}

	function draw() {
		background(backgroundColor);
		particles.forEach(particle => {
			particle.behaviors();
			particle.update();
			particle.show();
		});
	}

	//region core
	setup()
	draw()
	setInterval(draw, 16);

	function background(color) {
		fill(color);
		ctx.fillRect(0, 0, canvas.width, canvas.height);
	}

	function fill(color) {
		ctx.fillStyle = color;
	}

	function stroke(color) {
		ctx.strokeStyle = color;
	}

	function circle(x, y, radius) {
		ctx.beginPath();
		ctx.arc(x, y, radius, 0, 2 * Math.PI, true)
		ctx.fill();
		ctx.closePath();
	}

	function map(input, input_start, input_end, output_start, output_end) {
		return output_start + ((output_end - output_start) / (input_end - input_start)) * (input - input_start);
	}

	window.addEventListener('mousemove', (e) => {
		mouseX = e.clientX - canvas.getBoundingClientRect().x;
		mouseY = e.clientY - canvas.getBoundingClientRect().y;
	})

	//endregion

</script>
<?php endif;?>
