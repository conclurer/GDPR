<?php

namespace ProcessWire;
/**
 * ProcessWire  Module
 * Copyright (c) 2017 by Conclurer GmbH / Tomas Kostadinov
 *
 * This module provides hooks into the page rendering process and checks if the user has already agreed to gdpr rulings.
 *
 *
 * ProcessWire 3.x
 * Copyright (C) 2020 by Tomas Kostadinov / Conclurer GmbH
 * Licensed under MIT
 *
 * http://conclurer.com
 *
 *
 *
 */
class HookGDPR extends Wire implements Module {

	public static function getModuleInfo() {
		return [
			'title'    => 'Hooks for Conclurer\GDPR 🇪🇺',
			'version'  => 1,
			'summary'  => __('This module provides hooks into the page rendering process and checks if the user has already agreed to gdpr rulings', __FILE__),
			'author'   => 'Tomas Kostadinov / Conclurer GmbH',
			'href'     => 'https://conclurer.com',
			'requires' => 'ConfigureGDPR',
			'autoload' => true,
		];
	}

	public function init() {

		if($this->modules->isInstalled("ConsentModalGDPR")) {
			$this->addHookAfter('PageRender::renderPage', $this, 'checkGDPRConsentAndInjectModal');
		} else {
			$this->addHookBefore('PageRender::renderPage', $this, 'checkGDPRConsentAndRedirect');
		}
	}

	public function checkGDPRConsentAndRedirect($event) {
		if(wire('page')->rootParent->id == 2) return;

		/**
		 * @var ConfigureGDPR $configuration
		 * @var ConsentManagerGDPR $consent
		 *
		 */
		$configuration = $this->modules->get("ConfigureGDPR");
		$consent = $this->modules->get("ConsentManagerGDPR");

		$page = $configuration->getGDPRPage();
		if(wire('page')->id == $page->id) return;

		if($consent->userHasNotDecidedYet()) {
			wire('session')->redirect(
				$page->url . "?redirect=" . urlencode($this->page->url),
				false
			);
		}
	}


	public function checkGDPRConsentAndInjectModal($event) {

		if(wire('page')->rootParent->id == 2) return;

		/**
		 * @var ConfigureGDPR $configuration
		 * @var ConsentManagerGDPR $consent
		 * @var ConsentModalGDPR $module
		 *
		 */
		$configuration = $this->modules->get("ConfigureGDPR");
		$consent = $this->modules->get("ConsentManagerGDPR");
		$module = wire()->modules->get("ConsentModalGDPR");


		$page = $configuration->getGDPRPage();
		if(wire('page')->id == $page->id) return;

		if($consent->userHasNotDecidedYet()) {

			$output = str_replace("</body>", $module->render() . "</body>", $event->arguments()[0]->data("return"));
			$event->arguments()[0]->return = $output;
			/*
			wire('session')->redirect(
				$page->url . "?redirect=" . urlencode($this->page->url),
				false
			);*/
		}
	}


}