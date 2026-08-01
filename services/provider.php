<?php

/**
 * @package     JoomBoost.Site
 * @subpackage  mod_easyquickcontact
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\HelperFactory;
use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class () implements ServiceProviderInterface {
	public function register(Container $container): void
	{
		$container->registerServiceProvider(new ModuleDispatcherFactory('\\JoomBoost\\Module\\EasyQuickContact'));
		$container->registerServiceProvider(new HelperFactory('\\JoomBoost\\Module\\EasyQuickContact\\Site\\Helper'));
		$container->registerServiceProvider(new Module());
	}
};
