<?php


namespace Drupal\africassoc_forms\Form;


use Drupal;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\user\UserInterface;

class MemberRegisterForm extends FormBase {

  /**
   * @return string|void
   */
  public function getFormId() {
    return 'member_register_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['member_email'] = [
      '#type' => 'email',
      '#title' => 'Your Email',
      '#required' => TRUE,
      '#attributes' => [
        'size' => 40,
        'maxlength' => 40,
      ],
    ];
    $form['member_password'] = [
      '#type' => 'password_confirm',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => $this->t('Confirm Password'),
        'size' => 30,
        'maxlength' => 30,
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
    ];
    return $form;
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = trim($form_state->getValue('member_email'));
    $password = trim($form_state->getValue('member_password'));
    try {
      $existingUser = Drupal::entityTypeManager()
        ->getStorage('user')
        ->loadByProperties(['name' => $email]);
      if ($user = reset($existingUser)) {
        Drupal::messenger()
          ->addWarning($this->t('User already exists. <a href="@login-page">Please login.</a>', ['@login-page' => Url::fromRoute('user.login')->getInternalPath()]));
      }
      else {
        $language = Drupal::languageManager()->getCurrentLanguage()->getId();
        $user = User::create();
        if ($user instanceof UserInterface) {
          $user->setPassword($password);
          $user->enforceIsNew();
          $user->setEmail($email);
          $user->setUsername($email);
          $user->set('roles', ['member']);
          $user->set('langcode', $language);
          $user->set('status', 1);
          try {
            $user->save();
            $form_state->setRedirect('user.login');
            Drupal::messenger()->addStatus($this->t('Your account was created successfully'));
            Drupal::messenger()->addStatus($this->t('You may now login'));
          } catch (EntityStorageException $e) {
            Drupal::logger('africassoc_forms')
              ->error('Registering member failed: "{message}"', [
                'exception' => $e,
                'message' => $e->getMessage(),
              ]);
          }
        }
      }
    } catch (InvalidPluginDefinitionException $e) {
      Drupal::logger('africassoc_forms')->error('Invalid plugin: "{message}"', [
        'exception' => $e,
        'message' => $e->getMessage(),
      ]);
    } catch (PluginNotFoundException $e) {
      Drupal::logger('africassoc_forms')
        ->error('Plugin not found: "{message}"', [
          'exception' => $e,
          'message' => $e->getMessage(),
        ]);
    }
  }

}
