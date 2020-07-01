<?php

namespace ProcessWire;
/**
 * ProcessWire  Module
 *
 * This module provides a modal wrapper for forms for Conclurer\GDPR
 *
 *
 * Copyright (C) 2020 by Tomas Kostadinov / Conclurer GmbH
 * Licensed under MIT
 *
 * http://conclurer.com
 *
 *
 *
 */
class ConsentModalGDPR extends ConsentFormsGDPR implements Module {

	public static function getModuleInfo() {
		return [
			'title'    => 'Consent Modal for Consent Form Conclurer\GDPR 🇪🇺',
			'version'  => 1,
			'summary'  => __('This module provides a modal wrapper for forms for Conclurer\GDPR', __FILE__),
			'author'   => 'Tomas Kostadinov / Conclurer GmbH',
			'href'     => 'https://conclurer.com',
			'requires' => array('ConsentFormsGDPR', 'ConfigureGDPR'),
		];
	}

	private function getConfig() {

		/**
		 * @var ConfigureGDPR $configuration
		 *
		 */
		$configuration = $this->modules->get("ConfigureGDPR");

		/**
		 * @var ConfigureGDPR::configDefaults $d
		 */
		$d = $configuration->getData();
		return $d;
	}

	public function render() {
		$form = parent::render();

		$tpl = new TemplateFile(__DIR__ . '/templates/modal.inc.php');

		$tpl->content = $form;

		return $tpl->render();
	}


}