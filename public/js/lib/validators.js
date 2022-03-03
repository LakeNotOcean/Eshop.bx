export const validators = {
	names: 'names',
	email: 'email',
	phone: 'phone',
};

const validatorNameToHandler = {
	names: validateName,
	email: validateEmail,
	phone: validatePhone,
}

const validatorsConf = {
	names: [
		{
			validator: validateMinLen,
			params: [1],
			message: 'Длина должна быть более 1 символа'
		},
		{
			validator: validateMaxLen,
			params: [20],
			message: 'Длина должна быть менее 20 символов'
		},
		{
			validator: validateNameFormat,
			message: 'Неверный формат. Должны использоваться только кириллические и латинские символы'
		}
	],
	phone: [
		{
			validator: validatePhoneFormat,
			message: 'Неверный формат телефона'
		},
	],
	email: [
		{
			validator: validateEmailFormat,
			message: 'Неверный формат почты'
		}
	]
};

/**
 *
 * @param {{
 * 			validator: function,
 * 			message: string,
 * 			params: *
 * 		}} validatorConf
 * @param data
 * @return ?string
 */
function invokeDefaultValidator(validatorConf, ...data)
{
	if (validatorConf.hasOwnProperty('params'))
	{
		if (!validatorConf.validator(...data, ...validatorConf.params))
		{
			return validatorConf.message
		}
	}
	else
	{
		if (!validatorConf.validator(...data))
		{
			return validatorConf.message;
		}
	}

	return null;
}

/**
 *
 * @param {{
 * 			validator: function,
 * 			message: string,
 * 			params: *
 * 		}[]} conf
 * @param data
 * @return {string[]}
 */
function invokeDefaultValidators(conf, ...data)
{
	let errors = [];
	conf.forEach(
		validatorConf => {
			let validationResult = invokeDefaultValidator(validatorConf, ...data)
			if (validationResult !== null)
			{
				errors.push(validationResult)
			}
		}
	);

	return errors;
}

function validateName(name)
{
	return invokeDefaultValidators(validatorsConf.names, name);
}

function validateEmail(email)
{
	return invokeDefaultValidators(validatorsConf.email, email)
}

function validatePhone(phone)
{
	return invokeDefaultValidators(validatorsConf.phone, phone)
}

/**
 *
 * @param {{fieldName: string, validatorName: string, data: any}[]} fields validator name and validated data
 * @return {{fieldName: string, errors: string[]}[]}
 */
export function validateFields(fields)
{
	let errors = [];
	fields.forEach(
		field => {
			let validationErrors = validatorNameToHandler[field.validatorName](field.data);
			if (validationErrors.length !== 0)
			{
				errors.push(
					{
						fieldName: field.fieldName,
						errors: validationErrors,
					}
				);
			}
		},
	);

	return errors;
}

/**
 *
 * @param {string} data
 * @param {int} len
 * @return boolean
 */
function validateMinLen(data, len)
{
	return data.length >= len;
}

/**
 *
 * @param {string} data
 * @param {int} len
 * @return boolean
 */
function validateMaxLen(data, len)
{
	return data.length <= len;
}

/**
 *
 * @param {string} name
 */
function validateNameFormat(name)
{
	const regex = /[a-zа-яё]+/giu;
	return (name.length !== 0) && regex.test(name);
}

/**
 *
 * @param {string} phone
 */
function validatePhoneFormat(phone)
{
	const regex = /^(\+7|7|8)?[\s-]?\(?[489][0-9]{2}\)?[\s-]?[0-9]{3}[\s-]?[0-9]{2}[\s-]?[0-9]{2}$/u;
	return regex.test(phone)
}

/**
 *
 * @param {string} email
 */
function validateEmailFormat(email)
{
	const regex = /\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+/;
	return regex.test(email);
}