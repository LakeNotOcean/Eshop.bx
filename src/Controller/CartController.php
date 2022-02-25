<?php

namespace Up\Controller;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Service\CartService\CartServiceInterface;

class CartController
{
	private const itemIdPostKey = 'item-id';
	private $cartService;

	/**
	 * @param \Up\Service\CartService\CartService $cartService
	 */
	public function __construct(
		CartServiceInterface $cartService
	)
	{
		$this->cartService = $cartService;
	}

	/**
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
	public function addItemToCart(Request $request): Response
	{
		if (!$this->validAddOrDeletePostQuery($request))
		{
			return $this->getErrorResponse($request);
		}
		$itemId = $request->getPostParametersByName(static::itemIdPostKey);
		try
		{
			$this->cartService->addItemToCartById($itemId);
		}
		catch (\InvalidArgumentException $argumentException)
		{
			return (new Response())->withBodyJSON(
				[
					'success' => false,
					'errors' => [
						"Get invalid item id: {$itemId}"
					]
				]
			);
		}
		return (new Response())->withBodyJSON(
			[
				'success' => true
			]
		);
	}

	/**
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
	public function deleteItemFromCart(Request $request): Response
	{
		if (!$this->validAddOrDeletePostQuery($request))
		{
			return $this->getErrorResponse($request);
		}
		$itemId = $request->getPostParametersByName(static::itemIdPostKey);
		$this->cartService->deleteItemFromCart($itemId);
		return (new Response())->withBodyJSON(
			[
				'success' => true
			]
		);
	}

	private function getErrorResponse(Request $request): Response
	{
		if  (!$request->containsPost(static::itemIdPostKey))
		{
			return (new Response())->withBodyJSON(
				[
					'success' => false,
					'errors' => [
						'POST does not contains `item-id` attr'
					]
				]
			);
		}
		throw new \RuntimeException('Error with this request doesnt found');
	}

	private function validAddOrDeletePostQuery(Request $request): bool
	{
		return $request->containsPost('item-id');
	}
}