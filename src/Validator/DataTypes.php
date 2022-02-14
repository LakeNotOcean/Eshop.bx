<?php

namespace Up\Validator;

use Up\Core\Enum\Enum;

class DataTypes extends Enum
{
	const email=0;
	const phone=1;
	const login=2;
	const password=3;
	const names=4;
}