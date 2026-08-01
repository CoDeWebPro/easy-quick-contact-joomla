<?php

/**
 * @package     JoomBoost.Site
 * @subpackage  mod_easyquickcontact
 */

namespace JoomBoost\Module\EasyQuickContact\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Dispatcher for mod_easyquickcontact.
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
	use HelperFactoryAwareTrait;

	/**
	 * @return  array
	 */
	protected function getLayoutData()
	{
		$data   = parent::getLayoutData();
		$helper = $this->getHelperFactory()->getHelper('EasyQuickContactHelper');
		$moduleId = (int) $data['module']->id;

		$helper->loadAssets($data['params'], $data['app'], $moduleId);

		$formState = $helper->processSubmission($data['params'], $data['app'], $moduleId);

		return array_merge(
			$data,
			$helper->getDisplayData($data['params'], $data['app'], $moduleId),
			$formState
		);
	}
}
