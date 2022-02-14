<?php

namespace Up\Validator;

use Up\Core\Enum\Enum;

class ValidatorMethodEnum extends Enum
{
	const minLength = 0;
	const maxLength = 1;
	const minMaxValueInt = 2;
	const emailFormat = 3;
	const phoneFormat = 4;
	const nameFormat = 5;
	const numericFormat = 6;
	const onlyLatin = 8;
}

