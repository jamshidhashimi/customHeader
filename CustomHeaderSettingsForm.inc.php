<?php

/**
 * @file CustomHeaderSettingsForm.inc.php
 *
 * Copyright (c) 2013-2018 Simon Fraser University
 * Copyright (c) 2003-2018 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class CustomHeaderSettingsForm
 * @ingroup plugins_generic_customHeaders
 *
 * @brief Form for managers to modify custom header plugin settings
 */

import('lib.pkp.classes.form.Form');

class CustomHeaderSettingsForm extends Form {

	/** @var int */
	var $_contextId;

	/** @var object */
	var $_plugin;

	/**
	 * Constructor
	 * @param $plugin CustomHeaderPlugin
	 * @param $contextId int
	 */
	function __construct($plugin, $contextId) {
		$this->_contextId = $contextId;
		$this->_plugin = $plugin;

		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$this->_data = array(
			'header_content' => $this->_plugin->getSetting($this->_contextId, 'header_content'),
			'footer_content' => $this->_plugin->getSetting($this->_contextId, 'footer_content'),
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('header_content', 'footer_content'));
	}

	/**
	 * Fetch the form.
	 * @copydoc Form::fetch()
	 */
	function fetch($request, $template = null, $display = false) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		return parent::fetch($request, $template, $display);
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$this->_plugin->updateSetting($this->_contextId, 'header_content', $this->getData('header_content'), 'string');
		$this->_plugin->updateSetting($this->_contextId, 'footer_content', $this->getData('footer_content'), 'string');
	}
}