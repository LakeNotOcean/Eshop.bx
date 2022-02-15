<link rel="stylesheet" href="/css/404.css">

<div class="container">
	<div class="title-404">Страница 404!</div>
</div>
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
	let title404 = document.querySelector('.title-404');
	let container = document.querySelector('.container');
	let width = container.getBoundingClientRect().width;
	let height = container.getBoundingClientRect().height
		- title404.getBoundingClientRect().height
		- 140;
	let canvas = document.createElement('canvas');

	let rowLength = 40;
	let pieceSize = width/rowLength;

	canvas.width = Math.floor(width/pieceSize) * pieceSize;
	canvas.height = Math.floor(height/pieceSize) * pieceSize;
	container.append(canvas);
	let ctx = canvas.getContext('2d');
	//endregion init
	let columnHeight = Math.floor(height/pieceSize);

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
		background('#f7f7f7');

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
</script>