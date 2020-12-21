<?php

namespace ProcessWire;
/**
 * ProcessWire  Module
 *
 * This module provides opt-in and opt-out forms for Conclurer\GDP
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
class ConsentFormsGDPR extends Wire implements Module {

	public static function getModuleInfo() {
		return [
			'title'    => 'ConsentForms for Conclurer\GDPR 🇪🇺',
			'version'  => 4,
			'summary'  => __('This module provides opt-in and opt-out forms for Conclurer\GDPR', __FILE__),
			'author'   => 'Tomas Kostadinov / Conclurer GmbH',
			'href'     => 'https://conclurer.com',
			'requires' => 'ConfigureGDPR',
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

	private function tryToGetLanguageValue($key) {
		$data = $this->getConfig();
		try {
			if(isset($data[$key . "__" . $this->user->language()->id])) {
				if($data[$key . "__" . $this->user->language()->id] != "") {
					return $data[$key . "__" . $this->user->language()->id];
				}
			}
		} catch (WireException $exception) {
			// do nothing
		}
		return $data[$key];
	}

	public function render() {
		if(isset($this->input->get->redirect)) {
			$module = $this->getGDPRModule();
			if(isset($this->input->get->enableBlocking)) {
				$module->enableGDPRBlocking();
				$this->redirectPage($this->input->get->redirect);
			}
			if(isset($this->input->get->disableBlocking)) {
				$module->disableGDPRBlocking();
				$this->redirectPage($this->input->get->redirect);
			}
		}


		$tpl = new TemplateFile(__DIR__ . '/templates/form.inc.php');

		$tpl->text = $this->tryToGetLanguageValue("defaultOptText");
		$tpl->optOut = $this->tryToGetLanguageValue("defaultOptIn");
		$tpl->optIn = $this->tryToGetLanguageValue("defaultOptOut");
		$tpl->style = $this->tryToGetLanguageValue("defaultOptInStyle");

		if(isset($this->input->get->redirect)) {
			$url = urldecode($this->input->get->redirect);
			$tpl->url = urlencode($this->sanitizer->url($url));
		} else {
			// Check if modal version of GDPR form is used
			// then redirect to current page
			if($this->modules->isInstalled("ConsentModalGDPR")) {
				$tpl->url = urlencode($_SERVER['REQUEST_URI']);
			} else {
				$tpl->url = urlencode("/");
			}
		}

		return $tpl->render();
	}


	private function getGDPRModule() {

		/**
		 * @var ConsentManagerGDPR $module
		 *
		 */
		$module = $this->modules->get("ConsentManagerGDPR");

		return $module;
	}

	private function redirectPage($redirectStr) {
		if($redirectStr == "") {
			$url = "/";
		} else {
			$url = urldecode($redirectStr);
		}
		$this->wire()->session->redirect($url);
	}
}
