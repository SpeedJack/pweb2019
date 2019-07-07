<?php
namespace Pweb\Pages;

/**
 * @brief Represents a page.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
abstract class AbstractPage
{

// Protected Properties {{{
	/**
	 * @internal
	 * @var Pweb::App $_app
	 * The App instance.
	 */
	protected $_app;
	/**
	 * @internal
	 * @var Pweb::Entity::EntityManager $_em
	 * The Entity Manager instance.
	 */
	protected $_em;
	/**
	 * @internal
	 * @var Pweb::Entity::Visitor $_visitor
	 * The Visitor entity.
	 */
	protected $_visitor;

	/**
	 * @internal
	 * @var string $_title
	 * The title of this page.
	 */
	protected $_title;
	/**
	 * @internal
	 * @var array $_css
	 * An array containing all CSS files needed by the page.
	 */
	protected $_css = [];
	/**
	 * @internal
	 * @var array $_js
	 * An array containing all JavaScript files needed by the page.
	 */
	protected $_js = [];
	/**
	 * @internal
	 * @var array $_meta
	 * An array containing all \<meta\> HTML tags to be added in the
	 * \<head\> seciont of the page.
	 */
	protected $_meta = [];
// }}}

	/**
	 * @brief Creates a new page.
	 *
	 * @return	The created page.
	 */
	public function __construct()
	{
		$this->_app = \Pweb\App::getInstance();
		$this->_em = \Pweb\Entity\EntityManager::getInstance();
		$this->_visitor = $this->_em->create('Visitor');
	}

// Protected Methods {{{
	/**
	 * @internal
	 * @brief Sets the title of the page.
	 *
	 * @param[in] string $title	The title.
	 */
	protected function _setTitle($title)
	{
		$this->_title = htmlspecialchars($title, ENT_COMPAT | ENT_HTML5);
	}

	/**
	 * @internal
	 * @brief Returns the title of this page.
	 *
	 * @retval string	The title of this page.
	 */
	protected function _getTitle()
	{
		return $this->_title;
	}

	/**
	 * @internal
	 * @brief Adds a CSS to the page.
	 *
	 * @throws InvalidArgumentException	If the CSS file specified does
	 * 					not exists.
	 *
	 * @param[in] string $cssName	The CSS file name, without extension.
	 */
	protected function _addCss($cssName)
	{
		$cssDir = 'css';
		$cssFile = "$cssDir/$cssName.css";
		if (!file_exists($cssFile))
			throw new \InvalidArgumentException(
				__('The CSS file \'%s\' does not exists.',
					$cssFile)
			);
		$this->_css[$cssName] = $cssFile;
	}

	/**
	 * @internal
	 * @brief Adds a JavaScript file to the page.
	 *
	 * @throws InvalidArgumentException	If the JavaScript file specified
	 * 					does not exists.
	 *
	 * @param[in] string $scriptName	The JavaScript file name,
	 * 					without extension.
	 * @param[in] bool $defer		Specifies if the script must be
	 * 					loaded when the page has
	 * 					finished parsing (improves
	 * 					performance).
	 */
	protected function _addJs($scriptName, $defer = true)
	{
		$jsDir = 'js';
		$scriptFile = "$jsDir/$scriptName.js";
		if (!file_exists($scriptFile))
			throw new \InvalidArgumentException(
				__('The JavaScript file \'%s\' does not exists.',
					$scriptFile)
			);
		$this->_js[$scriptName] = [ 'file' => $scriptFile, 'defer' => $defer ];
	}

	/**
	 * @internal
	 * @brief Adds a \<meta\> entry to the page.
	 *
	 * @param[in] string $name	The meta name.
	 * @param[in] string $content	The meta content.
	 */
	protected function _addMeta($name, $content)
	{
		$name = htmlspecialchars($name, ENT_COMPAT | ENT_HTML5);
		$content = htmlspecialchars($content, ENT_COMPAT | ENT_HTML5);
		$this->_meta[$name] = $content;
	}

	/**
	 * @internal
	 * @brief Sets the viewport for the page.
	 *
	 * @param[in] string $viewport	The viewport value.
	 */
	protected function _setViewport($viewport)
	{
		$this->_addMeta('viewport', $viewport);
	}

	/**
	 * @internal
	 * @brief Sets the charset of the page.
	 *
	 * @param[in] string $charset	The charset.
	 */
	protected function _setCharset($charset)
	{
		$this->_addMeta('charset', $charset);
	}

	/**
	 * @internal
	 * @brief Returns a string containing all HTML tags that includes
	 * required CSS files.
	 *
	 * @retval string	The HTML output for CSS files inclusion.
	 */
	protected function _getCssTags()
	{
		$output = '';
		foreach ($this->_css as $cssFile)
			$output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$cssFile\">";
		return $output;
	}

	/**
	 * @internal
	 * @brief Returns a string containing all HTML tags that includes
	 * required JavaScript files.
	 *
	 * @retval string	The HTML output for JS file inclusion.
	 */
	protected function _getJsTags()
	{
		$output = '';
		foreach ($this->_js as $jsFile) {
			$defer = $jsFile['defer'] ? 'defer' : '';
			$output .= "<script src=\"${jsFile['file']}\" $defer></script>";
		}
		return $output;
	}

	/**
	 * @internal
	 * @brief Returns a string containing all HTML tags that adds metadata
	 * to the page.
	 *
	 * @retval string	The HTML output with \<meta\> tags.
	 */
	protected function _getMetaTags()
	{
		$charset = $this->_meta['charset'];
		unset($this->_meta['charset']);
		$output = "<meta charset=\"$charset\">";

		foreach ($this->_meta as $name => $content)
			$output .= "<meta name=\"$name\" content=\"$content\">";
		return $output;
	}

	/**
	 * @internal
	 * @brief Returns a string with the base skeleton for a HTML page.
	 *
	 * @retval string	The HTML skeleton.
	 */
	protected function _loadSkel()
	{
		$lang = __('en');
		$output = "<!DOCTYPE html>
			<html lang=\"$lang\">
			<head>
				<title>$this->_title</title>
			";
		$output .= $this->_getMetaTags();
		$output .= $this->_getCssTags();
		$output .= $this->_getJsTags();
		$output .= '</head>
			<body>';

		echo $output;
	}

	/**
	 * @internal
	 * @brief Loads a template.
	 *
	 * @param[in] string $templateName	The name of the template to
	 * 					load, without the extension.
	 * @param[in] array $params		Associative array of parameters
	 * 					to pass to the template.
	 * @retval bool				TRUE if the template was found;
	 * 					FALSE otherwise.
	 */
	protected function _loadTemplate($templateName, array $params = [])
	{
		$templateDir = \Pweb\App::APP_ROOT . '/templates';
		$templateName = "$templateDir/$templateName.php";
		if (!file_exists($templateName))
			$templateName = "$templateDir/$templateName.html";
		if (!file_exists($templateName))
			return false;

		extract($params);
		require_once 'template-functions.php';
		include $templateName;
		return true;
	}

	/**
	 * @internal
	 * @brief Loads the header template.
	 *
	 * @param[in] array $params	Associative array of parameters to pass
	 * 				to the header template.
	 */
	protected function _showHeader(array $params = [])
	{
		$this->_showContent('header', $params);
	}

	/**
	 * @internal
	 * @brief Loads the main content of a page.
	 *
	 * @throws InvalidArgumentException	If the template can not be
	 * 					found.
	 *
	 * @param[in] string $templateName	The name of the template to use
	 * 					as main content.
	 * @param[in] array $params		Associative array of parameters
	 * 					to pass to the template.
	 */
	protected function _showContent($templateName, array $params = [])
	{
		if (!$this->_loadTemplate($templateName, $params))
			throw new \InvalidArgumentException(
				__('The template \'%s\' does not exists.',
					$templateName)
			);
	}

	/**
	 * @internal
	 * @brief Loads the footer template.
	 *
	 * @param[in] array $params	Associative array of parameters to pass
	 * 				to the footer template.
	 */
	protected function _showFooter(array $params = [])
	{
		$this->_showContent('footer', $params);
	}

	/**
	 * @internal
	 * @brief Shows a full page, using a template as main content.
	 *
	 * @param[in] string $templateName	The name of the template to use
	 * 					as main content.
	 * @param[in] array $params		Associative array of parameters
	 * 					to pass to the template.
	 * @param[in] bool $showHeader		Set to FALSE to not load the
	 * 					header.
	 * @param[in] bool $showFooter		Set to FALSE to not load the
	 * 					footer.
	 * @param[in] bool $loadSkel		Set to FALSE to not load the
	 * 					HTML skeleton for the page
	 * 					(useful for AJAX responses).
	 */
	protected function _show($templateName, array $params = [],
		$showHeader = true, $showFooter = true, $loadSkel = true)
	{
		if ($showHeader) {
			$this->_addCss('header');
			$this->_addJs('sticky-navbar');
		}
		if ($showFooter)
			$this->_addCss('footer');

		if ($loadSkel) {
			$this->_addCss('main');
			$this->_addJs('main');
			$this->_addCss('modal');
			$this->_addJs('modal');
			$this->_addJs('language');
			$this->_addJs('responsive');
			if (!isset($this->_meta['charset']))
				$this->_setCharset('UTF-8');
			if (!isset($this->_meta['viewport']))
				$this->_setViewport('width=device-width, initial-scale=1.0');
			$this->_loadSkel();
		}

		$params['application'] = $this->_app;
		$params['visitor'] = $this->_visitor;

		if ($showHeader)
			$this->_showHeader($params);

		$this->_showContent($templateName, $params);

		if ($showFooter)
			$this->_showFooter($params);

		if ($loadSkel)
			echo '<div id="modal"></div>
			</body>
		</html>';

		flush();
	}

	/**
	 * @internal
	 * @brief Shows a modal to the visitor.
	 *
	 * @param[in] string $bodyTemplate		The name of the template
	 * 						to load in the modal.
	 * @param[in] string|null $redirect		The url to redirect the
	 * 						visitor when the modal
	 * 						is closed.
	 * @param[in] array $params			Associative array of
	 * 						parameters to pass to
	 * 						the template.
	 * @param[in] string|null $footerTemplate	The name of the modal
	 * 						footer template to load.
	 */
	protected function _showModal($bodyTemplate, $redirect = null, array $params = [], $footerTemplate = null)
	{
		$params['bodyTemplate'] = $bodyTemplate;
		$params['modalRedirect'] = $redirect;
		$params['footerTemplate'] = $footerTemplate;
		$this->_show('modal', $params, false, false, false);
	}

	/**
	 * @internal
	 * @brief Shows a modal with a footer to the visitor.
	 *
	 * @param[in] string $bodyTemplate	The name of the template to load
	 * 					in the modal's body.
	 * @param[in] string $footerTemplate	The name of the template to load
	 * 					in the modal's footer.
	 * @param[in] string|null $redirect	The url to redirect the visitor
	 * 					when the modal is closed.
	 * @param[in] array $params		Associative array of parameters
	 * 					to pass to the template.
	 */
	protected function _showModalWithFooter($bodyTemplate, $footerTemplate, $redirect = null, array $params = [])
	{
		$this->_showModal($bodyTemplate, $redirect, $params, $footerTemplate);
	}

	/**
	 * @internal
	 * @brief Shows a modal message to the visitor.
	 *
	 * @param[in] string $title		The message title.
	 * @param[in] string $message		The message body.
	 * @param[in] string|null $redirect	The url to redirect the visitor
	 * 					when the modal is closed.
	 */
	protected function _showMessage($title, $message, $redirect = null)
	{
		$this->_setTitle($title);
		$params = [];
		$params['message'] = $message;
		$this->_showModal('message', $redirect, $params);
	}

	/**
	 * @internal
	 * @brief Sends a simple reply to the visitor (useful for AJAX
	 * responses).
	 *
	 * @param[in] string $templateName	The name of the template to
	 * 					load.
	 * @param[in] array $params		Associative array of parameters
	 * 					to pass to the template.
	 */
	protected function _reply($templateName, array $params = [])
	{
		$this->_show($templateName, $params, false, false, false);
	}

	/**
	 * @internal
	 * @brief Redirects the user as a response of an AJAX request.
	 *
	 * @param[in] string $page	The page where the user should be
	 * 				redirected. If NULL, defaults to the
	 * 				home page.
	 * @param[in] string $action	The action where the user should be
	 * 				redirected. If NULL, defaults to
	 * 				actionIndex.
	 * @param[in] array $params	Associative array of key-value pairs of
	 * 				GET parameters.
	 */
	protected function _redirectAjax($page = null, $action = null, array $params = [])
	{
		$data = [
			'redirect' => true,
			'location' => $this->_app->buildLink($page, $action, $params)
		];
		$this->_replyJson($data);
	}

	/**
	 * @internal
	 * @brief Sends a json encoded reply to the visitor (useful for AJAX
	 * responses).
	 *
	 * @param[in] array $data	Associative array that will be json
	 * 				encoded and sent to the client.
	 */
	protected function _replyJson(array $data)
	{
		echo json_encode($data);
		flush();
	}
// }}}

// Abstract Methods {{{
	/** @brief The default action of the page. */
	abstract public function actionIndex();
// }}}

}
