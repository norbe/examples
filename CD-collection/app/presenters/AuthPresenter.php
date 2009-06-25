<?php

/*use Nette\Environment;*/
/*use Nette\Application\AppForm;*/
/*use Nette\Forms\Form;*/
/*use Nette\Security\AuthenticationException;*/

require_once dirname(__FILE__) . '/BasePresenter.php';


class AuthPresenter extends BasePresenter
{
	/** @persistent */
	public $backlink = '';



	public static function getPersistentParams()
	{
		return array('backlink');
	}



	public function actionLogin($backlink)
	{
		$form = new AppForm($this, 'form');
		$form->addText('username', 'Username:')
			->addRule(Form::FILLED, 'Please provide an username.');

		$form->addPassword('password', 'Password:')
			->addRule(Form::FILLED, 'Please provide a password.');

		$form->addSubmit('login', 'Login');
		$form->onSubmit[] = array($this, 'loginFormSubmitted');

		$form->addProtection('Please submit this form again (security token has expired).');

		$this->template->form = $form;
		$this->template->title = "Log in";
	}



	public function loginFormSubmitted($form)
	{
		try {
			$user = Environment::getUser();
			$user->authenticate($form['username']->getValue(), $form['password']->getValue());
			$this->getApplication()->restoreRequest($this->backlink);
			$this->redirect('Dashboard:');

		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}


}