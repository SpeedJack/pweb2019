<?php
namespace Pweb\Pages\Admin;

abstract class AbstractPage extends \Pweb\Pages\AbstractPage
{
	protected function _loadTemplate($templateName, array $params = [])
	{
		if (!parent::_loadTemplate("admin/$templateName", $params))
			parent::_loadTemplate($templateName, $params);
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
			parent::_addCss($scriptName, $defer);
		}
	}
}
