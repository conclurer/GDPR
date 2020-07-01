<?php

namespace ProcessWire;
/**
 * ProcessWire  Textformatter
 * Copyright (c) 2017 by Conclurer GmbH / Tomas Kostadinov
 *
 * This module implements a textformatter that filters content based on custom rules.
 *
 *
 * ProcessWire 3.x
 * Copyright (C) 2018 by Tomas Kostadinov / Conclurer GmbH
 * Licensed under MIT
 *
 * http://conclurer.com
 *
 *
 *
 */
class TextformatterGDPR extends Textformatter implements Module {

	public static function getModuleInfo() {
		return [
			'title'    => 'Textformatter for Conclurer\GDPR 🇪🇺',
			'version'  => 1,
			'summary'  => __('This module implements a textformatter that filters content based on custom rules', __FILE__),
			'author'   => 'Tomas Kostadinov / Conclurer GmbH',
			'href'     => 'https://conclurer.com',
			'requires' => ["ConfigureGDPR"],

		];
	}

	/**
	 * Text formatting function as used by the Textformatter interface
	 */
	public function format(&$str) {
		if(wire('page')->rootParent->id == 2) return;

		/**
		 * @var HookGDPR $module
		 *
		 */
		$module = $this->modules->get("ConsentManagerGDPR");

		if($module->userHasDeniedConsent()) {
			$str = $this->applyRules($str);
		}
	}

	public function applyRules($string, $filters = null, $force = false) {

		/**
		 * @var ConfigureGDPR $configuration
		 *
		 */
		$configuration = $this->modules->get("ConfigureGDPR");

		if($filters == null) {
			$filters = $configuration->getFilterRules();
		}
		/*
				if(!$force) {
					if($this->gdprBlockingIsEnabled()) {
					}
				}
		*/
		foreach ($filters as $filter) {
			$string = preg_replace("/" . $filter[0] . "/", $filter[1], $string);
		}
		return $string;
	}
}