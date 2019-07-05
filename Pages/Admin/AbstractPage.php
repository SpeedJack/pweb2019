<?php
namespace Pweb\Pages\Admin;

abstract class AbstractPage extends \Pweb\Pages\AbstractPage
{
	protected function _loadTemplate($templateName, array $params = [])
	{
		$success = parent::_loadTemplate("admin/$templateName", $params);
		if (!$success)
			return parent::_loadTemplate($templateName, $params);
		return $success;
	}

	protected function _addCss($cssName)
	{
		try {
			parent::_addCss("admin/$cssName");
		} catch (\InvalidArgumentException $e) {
			parent::_addCss($cssName);
		}
	}

	protected function _addJs($scriptName, $defer = true)
	{
		try {
			parent::_addJs("admin/$scriptName", $defer);
		} catch (\InvalidArgumentException $e) {
			parent::_addJs($scriptName, $defer);
		}
	}
}
