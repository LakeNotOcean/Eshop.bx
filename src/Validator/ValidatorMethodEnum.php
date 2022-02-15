<?php

namespace Up\Validator;

use Up\Core\Enum\Enum;

class ValidatorMethodEnum extends Enum
{
	const minLength = 'minLength';
	const maxLength = 'maxLength';
	const minMaxValueInt = 'minMaxValueInt';
	const emailFormat = 'emailFormat';
	const phoneFormat = 'phoneFormat';
	const nameFormat = 'nameFormat';
	const numericFormat = 'numericFormat';
	const onlyLatin = 'onlyLatin';
}

