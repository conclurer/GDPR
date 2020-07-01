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

	public function userHasNotDecidedYet() {
		return !$this->userHasGivenConsent() && !$this->userHasDeniedConsent();
	}

	public function enableGDPRBlocking() {
		$this->input->cookie->set('gdprBlockingEnabled', 'Please don not look here', ['age' => 86400 * 365, 'path' => '/']);
		$this->input->cookie->remove('gdprBlockingDisabled');
	}

	public function disableGDPRBlocking() {
		$this->input->cookie->remove('gdprBlockingEnable');
		$this->input->cookie->set('gdprBlockingDisabled', 'Please don not look here', ['age' => 86400 * 365, 'path' => '/']);
	}

	public function consent($true, $false = "") {
		if($this->userHasGivenConsent()) {
			return $true;
		} else {
			if($false == "") {
				/**
				 * @var ConfigureGDPR $configuration
				 *
				 */
				$configuration = $this->modules->get("ConfigureGDPR");

				$false = '<div class="gdpr-message">' . $configuration->getDisabledText() . "</div>";
			}
			return $false;
		}
	}

}