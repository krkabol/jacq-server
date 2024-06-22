<?php declare(strict_types = 1);

namespace App\UI\Form;

final class FormFactory
{

	public function create(): BaseForm
	{
		return new BaseForm();
	}


}
