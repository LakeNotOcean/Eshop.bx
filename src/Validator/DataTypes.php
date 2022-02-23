<?php

namespace Up\Validator;

use Up\Core\Enum\Enum;

class DataTypes extends Enum
{
	const email='email';
	const phone='phone';
	const login='login';
	const password='password';
	const names='names';
	const rating='rating';
	const reviewText='reviewText';
}