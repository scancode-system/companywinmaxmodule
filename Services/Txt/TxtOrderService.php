<?php

namespace Modules\CompanyWinmax\Services\Txt;

use Illuminate\Support\Facades\Storage;
use Modules\Order\Repositories\OrderRepository;
use Modules\Dashboard\Services\Txt\TxtService;
use  ZipArchive;

class TxtOrderService extends TxtService
{

	public function build()
	{
		$orders = OrderRepository::loadClosedOrders();
		foreach ($orders as $order) 
		{
			$file_path = $this->file_path($order);
			$this->header($file_path, $order);

			foreach ($order->items as $item) 
			{				
				$this->item($file_path, $item);
			}
		}
	}

	private function header($file_path, $order)
	{
		Storage::append($file_path, 
			'*' .
			mb_substr(addString($order->id, 8, '0'), 0, 8).
			mb_substr(addString($order->order_client->client_id, 5, '0'), 0, 5).
			mb_substr($order->closing_date, 8, 2).
			mb_substr($order->closing_date, 5, 2).
			mb_substr($order->closing_date, 0, 4).
			mb_substr($order->closing_date, 11, 2). 
			mb_substr($order->closing_date, 14, 2).
			mb_substr(addString($order->order_saller->saller_id, 3, '0'), 0, 3).
			mb_substr(addString($order->order_payment->payment_id, 2, '0'), 0, 2).
			'00000');
	}

	private function item($file_path, $item)
	{
		$tax_ipi = $item->item_taxes()->where('module', 'ipi')->first();
		if($tax_ipi)
		{
			$ipi = $tax_ipi->porcentage;
		}else
		{
			$ipi = 0;
		}

		Storage::append($file_path, 
			mb_substr(addString($item->product->sku, 15, ' ', false), 0, 15). 
			mb_substr(addString($item->qty, 6, '0'), 0, 6).
			mb_substr(addString(number_format(($item->price/2), 2, '', ''), 7, '0'), 0, 7).
			mb_substr(addString(number_format($item->discount, 2, '', ''), 7, '0'), 0, 7).
			mb_substr(addString(number_format($ipi, 2, '', ''), 5, '0'), 0, 5));
	}	

	private function file_path($order)
	{
		return $this->path_base.mb_substr(addString($order->id, 7, '0'), 0, 7). '.txt';
	}

	private function subsidiary_id($item)
	{
		if($item->product->subsidiaries_product){
			return $item->product->subsidiaries_product->subsidiary_id;
		} else {
			return '';
		}
	}

}


