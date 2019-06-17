<?php
namespace Pweb\Pages;

/**
 * @brief Represents the about page.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class AboutPage extends AbstractPage
{
	/**
	 * @brief Loads a frame that shows the about page.
	 */
	public function actionIndex()
	{
		$this->_setTitle(__('About Page'));
		$this->_show('frame', [ 'frameSrc' => 'about.html' ]);
	}
}
