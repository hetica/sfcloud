<?php
namespace HB\BlogBundle\Controller;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Symfony\Component\DependencyInjection\ContainerAware;

class DemoController extends ContainerAware
{
	/**
	 * @Soap\Method("hello")
	 * @Soap\Param("name", phpType = "string")
	 * @Soap\Result(phpType = "string")
	 */
	public function helloAction($name)
	{
		return sprintf('Hello %s!', $name);
	}

	/**
	 * @Soap\Method("goodbye")
	 * @Soap\Param("name", phpType = "string")
	 * @Soap\Result(phpType = "string")
	 */
	public function goodbyeAction($name)
	{
		return $this->container->get('besimple.soap.response')->setReturnValue(sprintf('Goodbye %s!', $name));
	}
}