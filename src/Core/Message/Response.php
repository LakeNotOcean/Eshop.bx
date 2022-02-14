<?php

namespace Up\Core\Message;


class Response
{
	protected $statusCode = 200;
	protected $reason = '';
	protected $body = '';
	protected $headers = [];

	public static $statusTexts = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',            // RFC2518
		103 => 'Early Hints',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',          // RFC4918
		208 => 'Already Reported',      // RFC5842
		226 => 'IM Used',               // RFC3229
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',    // RFC7238
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Content Too Large',                                           // RFC-ietf-httpbis-semantics
		414 => 'URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',                                               // RFC2324
		421 => 'Misdirected Request',                                         // RFC7540
		422 => 'Unprocessable Content',                                       // RFC-ietf-httpbis-semantics
		423 => 'Locked',                                                      // RFC4918
		424 => 'Failed Dependency',                                           // RFC4918
		425 => 'Too Early',                                                   // RFC-ietf-httpbis-replay-04
		426 => 'Upgrade Required',                                            // RFC2817
		428 => 'Precondition Required',                                       // RFC6585
		429 => 'Too Many Requests',                                           // RFC6585
		431 => 'Request Header Fields Too Large',                             // RFC6585
		451 => 'Unavailable For Legal Reasons',                               // RFC7725
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',                                     // RFC2295
		507 => 'Insufficient Storage',                                        // RFC4918
		508 => 'Loop Detected',                                               // RFC5842
		510 => 'Not Extended',                                                // RFC2774
		511 => 'Network Authentication Required',                             // RFC6585
	];

	/** PSR-7:
	 * Despite that headers may be retrieved case-insensitively,
	 * the original case MUST be preserved by the implementation.
	 *
	 * PSR-7:
	 * Messages are considered immutable; all methods that might change state MUST
	 * be implemented such that they retain the internal state of the current
	 * message and return an instance that contains the changed state.
	 **/
	public function withAddedHeader(string $name, string $value): Response
	{
		$copy = clone $this;
		$copy->addHeader($name, $value);

		return $copy;
	}

	public function withoutHeader(string $name): Response
	{
		$copy = clone $this;
		$nameLowerCase = strtolower($name);
		unset($copy->headers[$nameLowerCase]);

		return $copy;
	}

	public function withStatus(int $status): Response
	{
		$copy = clone $this;
		$copy->statusCode = $status;
		if (isset(Response::$statusTexts[$status]))
		{
			$copy->reason = Response::$statusTexts[$status];
		}

		return $copy;
	}

	public function withBodyHTML(string $body): Response
	{
		$copy = clone $this;
		$copy->body = $body;
		$copy->setHeader('Content-Type', 'text/html');

		return $copy;
	}

	public function withBodyJSON(array $data): Response
	{
		$copy = clone $this;
		$copy->body = json_encode($data);
		$copy->setHeader('Content-Type', 'application/json');

		return $copy;
	}

	public function flush()
	{
		$this->writeHeaders();
		echo $this->body;
	}

	private function setHeader(string $name, string $value)
	{
		$nameLowerCase = strtolower($name);
		$this->headers[$nameLowerCase] = new Header($name, $value);
	}

	private function addHeader(string $name, string $value)
	{
		$nameLowerCase = strtolower($name);
		if (!isset($this->headers[$nameLowerCase]))
		{
			$this->headers[$nameLowerCase] = new Header($name);
		}
		$this->headers[$nameLowerCase]->addValue($value);
	}

	private function writeHeaders()
	{
		if ($this->statusCode != 200)
		{
			header("HTTP/1.1 {$this->statusCode} {$this->reason}");
		}
		foreach ($this->headers as $header)
		{
			header("{$header->getName()}: {$header->getValuesLine()}");
		}
	}
}
