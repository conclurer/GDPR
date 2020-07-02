<?php

namespace ProcessWire;
/**
 * ProcessWire  Module
 *
 * This module provides the settings for HookGDPR and TextformatterGDPR.
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
class ConfigureGDPR extends WireData implements ConfigurableModule, Module {

	public static function getModuleInfo() {
		return [
			'title'    => 'Configuration for Conclurer\GDPR 🇪🇺',
			'version'  => 1,
			'summary'  => __('This module provides the settings for HookGDPR and TextformatterGDPR', __FILE__),
			'author'   => 'Tomas Kostadinov / Conclurer GmbH',
			'href'     => 'https://conclurer.com',
			'autoload' => true,
			'installs' => ["ConsentManagerGDPR", "HookGDPR", "TextformatterGDPR", "ConsentFormsGDPR"],
		];
	}

	protected static $configDefaults = [
		"defaultContentHiddenText" => "This content has been blocked. Please Opt-In External Sources <a href='/'>here</a> to allow this content.",
		"defaultOptText"           => "<h1>GDPR Info</h1> We use cookies and similar methods to recognize visitors and remember their preferences. We also use them to measure ad campaign effectiveness, target ads and analyze site traffic. To learn more about these methods, including how to disable them, <a href=\"/privacy\">view our Cookie Policy</a>. By clicking ‘accept,’ you consent to the processing of your data by us and third parties using the above methods. You can always change your tracker preferences by visiting this page.",
		"defaultOptIn"             => "Accept",
		"defaultOptOut"            => "Decline",
		"defaultFilterRules"       => '"<iframe[^>]+src="([^"]+)"";"<iframe src="/gdprnotice" style=\'width: 100%; min-height:400px\' "
"(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$";"{message}"',
		"styles"                   => "<style>.gdpr-message{ padding: 20px 10px; border: 1px solid #000 }</style>",
		"selectGDPRPage"           => "1",
		"defaultOptInStyle"        => "
<style>
    .gdpr-form .accept-form,
    .gdpr-form .deny-form {
        display: inline;
        padding: 15px;
    }

    .gdpr-form .accept-form .gdpr-submit,
    .gdpr-form .deny-form .gdpr-submit {
        padding: 15px;
        background: none;
        box-shadow: none;
        border-radius: 0;
        border: 0;
        cursor: pointer;
        color: white;
    }

    .gdpr-form .accept-form .gdpr-submit {
        background: #4cd137;
    }

    .gdpr-form .deny-form .gdpr-submit {
        background: #e84118;
    }

    .gdpr-form .center{
        text-align: center;
    }
</style>",
		"defaultModalStyle"        => "<style>
    .modal {
        position: fixed;
        z-index: 10000;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(0.8);
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

</style>",
	];


	protected $data = [];

	private function loadData(array $data) {
		foreach (self::$configDefaults as $key => $value) {
			if(empty($data[$key])) $data[$key] = $value;
		}
		return $data;
	}

	/**
	 * Module configuration screen
	 *
	 */
	public function getModuleConfigInputfields(array $data) {

		$fieldsetUsage = new InputfieldFieldset();
		$fieldsetUsage->label = "Usage & Api";
		$fieldsetUsage->icon = "book";
		$fieldsetUsage->collapsed = InputfieldFieldset::collapsedYes;


		$fieldsetSettings = new InputfieldFieldset();
		$fieldsetSettings->label = "Blocking Settings & Filters";
		$fieldsetSettings->icon = "filter";
		$fieldsetSettings->collapsed = InputfieldFieldset::collapsedYes;

		$fieldsetOpt = new InputfieldFieldset();
		$fieldsetOpt->icon = "check";
		$fieldsetOpt->label = "Opt-In and Opt-Out Settings";
		$fieldsetOpt->collapsed = InputfieldFieldset::collapsedYes;


		$fieldsetTest = new InputfieldFieldset();
		$fieldsetTest->icon = "question";
		$fieldsetTest->label = "Test the configuration";
		$fieldsetTest->collapsed = InputfieldFieldset::collapsedYes;

		$fieldsetModal = new InputfieldFieldset();
		$fieldsetModal->icon = "object-group";
		$fieldsetModal->label = "Modal Configuration";
		$fieldsetModal->collapsed = InputfieldFieldset::collapsedYes;


		/*********************************
		 *
		 * Usage Documentation
		 *
		 *********************************/
		/**
		 * @var InputfieldTextarea $f
		 *
		 */

		$f = wire('modules')->get('InputfieldTextarea');
		$f->label = 'PHP-Code for the opt-in and opt-out form';
		$f->value = "Hal";
		$f->name = "test";
		$f->attr('value', $data["defaultContentHiddenText"]);
		$fieldsetUsage->add($f);

		/*********************************
		 *
		 * Blocking Settings
		 *
		 *********************************/

		$data = $this->loadData($data);
		/**
		 * @var InputfieldPageListSelect $f
		 *
		 */
		$f = wire('modules')->get('InputfieldPageListSelect');
		$f->name = 'selectGDPRPage';
		$f->label = 'GDPR Settings Page';
		$f->description = "This page will be used as a settings page - new users will be redirected there to decide whether they want to opt-in or opt-out.";
		$f->attr('value', $data["selectGDPRPage"]);
		$fieldsetSettings->add($f);


		/**
		 * @var InputfieldCKEditor $f
		 *
		 */
		$f = wire('modules')->get('InputfieldCKEditor');
		$f->name = 'defaultContentHiddenText';
		$f->label = 'Content Filtered Notice';
		$f->useLanguages = true;
		$f->description = "\"This content has been blocked\" - Text";
		$f->attr('value', $data["defaultContentHiddenText"]);
		$fieldsetSettings->add($f);

		/**
		 * @var InputfieldTextarea $f
		 *
		 */
		$f = wire('modules')->get('InputfieldTextarea');
		$f->name = 'styles';
		$f->label = 'Styling';
		$f->description = "Default is " . self::$configDefaults["styles"];
		$f->attr('value', $data["styles"]);
		$fieldsetSettings->add($f);

		/**
		 * @var InputfieldTextarea $f
		 *
		 */
		$f = wire('modules')->get('InputfieldTextarea');
		$f->name = 'defaultFilterRules';
		$f->label = 'Regex Filter Rules';
		$f->description = "Comma Separated Filter rules";
		$f->notes = 'Please use the following CSV template (use {message} for replacement with the above specified message):';
		$f->detail = self::$configDefaults["defaultFilterRules"];
		$f->attr('value', $data["defaultFilterRules"]);
		$fieldsetSettings->add($f);

		/*********************************
		 *
		 * Opt-In and Opt-Out Settings
		 *
		 *********************************/

		/**
		 * @var InputfieldCKEditor $f
		 *
		 */
		$f = wire('modules')->get('InputfieldCKEditor');
		$f->name = 'defaultOptText';
		$f->label = 'Opt-In / Opt-Out Text';
		$f->useLanguages = true;
		$f->description = "This page will be shown on the opt-in page.";
		$f->attr('value', $data["defaultOptText"]);
		$fieldsetOpt->add($f);

		/**
		 * @var InputfieldText $f
		 *
		 */
		$f = wire('modules')->get('InputfieldText');
		$f->name = 'defaultOptIn';
		$f->label = 'Opt-In button Label';
		$f->useLanguages = true;
		$f->description = "This is the label of the opt-in button";
		$f->columnWidth = 50;
		$f->attr('value', $data["defaultOptIn"]);
		$fieldsetOpt->add($f);

		/**
		 * @var InputfieldText $f
		 *
		 */
		$f = wire('modules')->get('InputfieldText');
		$f->name = 'defaultOptOut';
		$f->label = 'Opt-In button Label';
		$f->useLanguages = true;
		$f->description = "This is the label of the opt-in button";
		$f->columnWidth = 50;
		$f->attr('value', $data["defaultOptOut"]);
		$fieldsetOpt->add($f);


		/**
		 * @var InputfieldTextarea $f
		 *
		 */
		$f = wire('modules')->get('InputfieldTextarea');
		$f->name = 'defaultOptInStyle';
		$f->label = 'Opt-In / Opt-Out Page Styling';
		$f->attr('value', $data["defaultOptInStyle"]);
		$fieldsetOpt->add($f);

		/**
		 * @var TextformatterGDPR $textformatter
		 *
		 */
		$textformatter = $this->modules->get("TextformatterGDPR");

		/**
		 * @var InputfieldMarkup $f
		 *
		 */

		$f = wire('modules')->get('InputfieldMarkup');
		$f->label = 'Regex Filter Test.';
		$f->notes = 'The above specified rules create this output';
		$f->detail = '<p>Hallo <iframe src="https://google.com"></iframe> https://www.youtube.com/watch?v=kwKrNtq9gHI</p>';
		$test = $textformatter->applyRules($f->detail, null, true);
		$f->detail = "Original was: " . $f->detail;
		$f->description = $test;
		$fieldsetTest->add($f);

		$inputFields = new InputfieldWrapper();

		$inputFields->add($fieldsetUsage);
		$inputFields->add($fieldsetSettings);
		$inputFields->add($fieldsetOpt);

		/*********************************
		 *
		 * Optional Settings (Extensions like the modal)
		 *
		 *********************************/

		if($this->modules->isInstalled("ConsentModalGDPR")) {

			/**
			 * @var InputfieldTextarea $f
			 *
			 */
			$f = wire('modules')->get('InputfieldTextarea');
			$f->name = 'defaultModalStyle';
			$f->label = 'Styling for the Modal';
			$f->attr('value', $data["defaultModalStyle"]);

			$fieldsetModal->add($f);
			$inputFields->add($fieldsetModal);
		}

		$inputFields->add($fieldsetTest);

		return $inputFields;
	}

	public function getDisabledText() {
		$data = $this->loadData($this->data);

		return $data["defaultContentHiddenText"];
	}

	public function getFilterRules() {
		$data = $this->data();
		foreach (self::$configDefaults as $key => $value) {
			if(!isset($data[$key]) || $data[$key] == '') $data[$key] = $value;
		}

		$csv = [];
		$itemPerLine = explode("\n", $data["defaultFilterRules"]);
		foreach ($itemPerLine as $line) {
			$l = explode('";"', $line);
			$l[0] = ltrim($l[0], '"');
			$l[1] = rtrim($l[1], '"');
			$l[1] = str_replace('{message}', $data["styles"] . '<div class="gdpr-message">' . $data["defaultContentHiddenText"] . '</div>', $l[1]);
			array_push($csv, $l);
		}
		return $csv;
	}

	public function getGDPRPage() {
		$data = $this->loadData($this->data);
		$page = $this->pages->get($data["selectGDPRPage"]);
		return $page;
	}

	public function getData() {
		return $this->loadData($this->data);
	}
}