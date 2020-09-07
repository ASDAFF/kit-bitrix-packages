<?
namespace Kit\Origami\Sale\Basket;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\Result;
use Bitrix\Main\SystemException;
use Bitrix\Main\Error;
use Bitrix\Sale\BasketItem;

class Buy extends Base
{
	/**
	 * @param BasketItem $basketItem
	 * @throws ArgumentOutOfRangeException
	 */

	protected function beforeAddItem(&$basketItem)
	{

	}

	/**
	 * @return Result
	 * @throws ArgumentOutOfRangeException
	 * @throws \Bitrix\Main\ObjectNotFoundException
	 */
	public function remove()
	{
		$result = new Result();
		try
		{
			Loader::includeModule('sale');
		}
		catch (LoaderException $e)
		{
			$result->addError(new Error($e->getTraceAsString()));
		}
		try
		{
			if(!$this->getId())
			{
				throw new SystemException('Not id');
			}
		}
		catch (SystemException $e)
		{
			$result->addError(new Error($e->getTraceAsString()));
		}

		$existed = $this->isExist();

		if($existed)
		{
			$existed->delete();
			$this->basket->save();
		}
		return $result;
	}
/*
	public function findWishes()
	{
		$return = [];

		foreach ($this->basket as $basketItem)
		{
			if ($basketItem->getField('DELAY') == 'Y')
			{
				$return[] = $basketItem->getField('PRODUCT_ID');
			}
		}
		return array_unique($return);
	}*/

	/**
	 * @return bool|BasketItem
	 */
	protected function isExist()
	{
		$return = false;

		foreach ($this->basket as $basketItem)
		{
			if ($basketItem->getField('PRODUCT_ID') == $this->getId())
			{
				$return = $basketItem;
				break;
			}
		}
		return $return;
	}
}