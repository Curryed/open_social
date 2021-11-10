<?php

namespace Drupal\social_group_request\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\Url;
use Drupal\group\Entity\GroupInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides a form to request group membership for anonymous.
 */
class GroupRequestMembershipRequestAnonymousForm extends FormBase {

  /**
   * Group entity.
   *
   * @var \Drupal\group\Entity\GroupInterface
   */
  protected ?GroupInterface $group = NULL;

  /**
   * GroupRequestMembershipRejectForm constructor.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation.
   */
  public function __construct(TranslationInterface $string_translation) {
    $this->setStringTranslation($string_translation);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('string_translation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'social_group_request_membership_request_anonymous';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, GroupInterface $group = NULL): array {
    $this->group = $group;

    $form['description'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('In order to send your request, please first sign up or log in.'),
    ];

    $previous_url = $this->requestStack->getCurrentRequest()->headers->get('referer');
    $request = Request::create($previous_url);
    $referer_path = $request->getRequestUri();

    $form['actions']['#type'] = 'actions';
    $form['actions']['sign_up'] = [
      '#type' => 'link',
      '#title' => $this->t('Sign up'),
      '#attributes' => [
        'class' => [
          'btn',
          'btn-primary',
          'waves-effect',
          'waves-btn',
        ],
      ],
      '#url' => Url::fromRoute('user.register', [
        'destination' => $referer_path . '?requested-membership=' . $this->group->id(),
      ]),
    ];

    $form['actions']['log_in'] = [
      '#type' => 'link',
      '#title' => $this->t('Log in'),
      '#attributes' => [
        'class' => [
          'btn',
          'btn-default',
          'waves-effect',
          'waves-btn',
        ],
      ],
      '#url' => Url::fromRoute('user.login', [
        'destination' => $referer_path . '?requested-membership=' . $this->group->id(),
      ]),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {}

}
