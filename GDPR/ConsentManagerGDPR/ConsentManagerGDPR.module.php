<?php

namespace ProcessWire;
/**
 * ProcessWire  Module
 *
 * This module manages the state of the user opt-in and opt-out
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
class ConsentManagerGDPR extends Wire implements Module {

	public static function getModuleInfo() {
		return [
			'title'    => 'Consent Manager for Conclurer\GDPR 🇪🇺',
			'version'  => 1,
			'summary'  => __('This module manages the state of the user opt-in and opt-out', __FILE__),
			'author'   => 'Tomas Kostadinov / Conclurer GmbH',
			'href'     => 'https://conclurer.com',
			'requires' => 'ConfigureGDPR',
		];
	}

	public function userHasGivenConsent() {
		return $this->input->cookie->get("gdprBlockingEnabled") != null;
	}

	public function userHasDeniedConsent() {
		return $this->input->cookie->get("gdprBlockingDisabled") != null;
	}

	public function enableGDPRBlocking() {
		$this->input->cookie->set('gdprBlockingEnabled', 'Please don`t look here', ['age' => 86400 * 365, 'path' => '/']);
		$this->input->cookie->remove('gdprBlockingDisabled');
	}

	public function disableGDPRBlocking() {
		$this->input->cookie->remove('gdprBlockingEnable');
		$this->input->cookie->set('gdprBlockingDisabled', 'Please don`t look here', ['age' => 86400 * 365, 'path' => '/']);
	}

}