<?php
declare(strict_types=1);

namespace SchoolWiseCommerceControls\Service;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class CustomFieldsInstaller
{
    private const CUSTOM_FIELDSET_NAME = 'school_information';
    private const SCHOOL_SHIPPING_INFORMATION_NAME = 'school_shipping_information';

    private const CUSTOM_FIELDSET = [
        [
            'name' => self::CUSTOM_FIELDSET_NAME,
            'config' => [
                'label' => [
                    'en-GB' => 'School Information',
                    'de-DE' => 'Schulinformationen'
                ]
            ],
            'customFields' => [
                [
                    'name' => 'school_key',
                    'type' => CustomFieldTypes::TEXT,
                    'config' => [
                        'componentName' => 'sw-field',
                        'customFieldType' => 'text',
                        'label' => [
                            'en-GB' => 'Add School Key',
                            'de-DE' => 'Schulschlüssel hinzufügen'
                        ],
                        'customFieldPosition' => 1
                    ]
                ],
                [
                    'name' => 'shipping_enabled_for_home',
                    'type' => CustomFieldTypes::BOOL,
                    'config' => [
                        'componentName' => 'sw-field',
                        'customFieldType' => 'checkbox',
                        'label' => [
                            'en-GB' => 'Shipping enabled for Home?',
                            'de-DE' => 'Versand für Zuhause aktiviert?'
                        ],
                        'customFieldPosition' => 2
                    ]
                ]
            ]
        ],


    ];

    public function __construct(
        private readonly EntityRepository $customFieldSetRepository,
        private readonly EntityRepository $customFieldSetRelationRepository
    )
    {
    }

    public function install(Context $context): void
    {
        foreach ($this->getCustomFieldsets() as $fieldset) {
            if (count($this->getCustomFieldSetIds($fieldset['name'], $context)) === 0) {
                $this->customFieldSetRepository->upsert([$fieldset], $context);
            }
        }
    }

    public function addRelations(Context $context): void
    {
        foreach ($this->getCustomFieldsets() as $fieldset) {
            $customFieldSetIds = $this->getCustomFieldSetIds($fieldset['name'], $context);

            foreach ($customFieldSetIds as $customFieldSetId) {
                if (!$this->relationExists($customFieldSetId, 'customer_group', $context)) {
                    $this->customFieldSetRelationRepository->upsert([
                        [
                            'customFieldSetId' => $customFieldSetId,
                            'entityName' => 'customer_group',
                        ]
                    ], $context);
                }
            }
        }
    }

    /**
     * @return string[]
     */
    public function getCustomFieldSetIds(string $name, Context $context): array
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->customFieldSetRepository->searchIds($criteria, $context)->getIds();
    }


    private function relationExists(string $customFieldSetId, string $entityName, Context $context): bool
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('customFieldSetId', $customFieldSetId));
        $criteria->addFilter(new EqualsFilter('entityName', $entityName));

        $result = $this->customFieldSetRelationRepository->search($criteria, $context);

        return $result->getTotal() > 0;
    }

    public function deleteCustomFieldSets(array $customFieldSetIds, Context $context): void
    {
        foreach ($customFieldSetIds as $id) {
            $this->customFieldSetRepository->delete([['id' => $id]], $context);
        }
    }

    private function getCustomFieldsets(): array
    {
        return [
            [
                'name' => self::CUSTOM_FIELDSET_NAME,
                'config' => [
                    'label' => [
                        'en-GB' => 'School Information',
                        'de-DE' => 'Schulinformationen',
                    ],
                ],
                'customFields' => [
                    [
                        'name' => 'school_key',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => $this->textFieldConfig('Add School Key', 'Schulschlüssel hinzufügen', 1),
                    ],
                    [
                        'name' => 'shipping_enabled_for_home',
                        'type' => CustomFieldTypes::BOOL,
                        'config' => $this->checkboxFieldConfig('Shipping enabled for Home?', 'Versand für Zuhause aktiviert?', 2),
                    ],
                ],
            ],
            [
                'name' => self::SCHOOL_SHIPPING_INFORMATION_NAME,
                'config' => [
                    'label' => [
                        'en-GB' => 'School Shipping Information',
                        'de-DE' => 'Schulversandinformationen',
                    ],
                ],
                'customFields' => [
                    ['name' => 'school_salutation', 'type' => CustomFieldTypes::TEXT, 'config' => $this->textFieldConfig('Salutation', 'Anrede', 1)],
                    ['name' => 'school_first_name', 'type' => CustomFieldTypes::TEXT, 'config' => $this->textFieldConfig('First Name', 'Vorname', 2)],
                    ['name' => 'school_last_name', 'type' => CustomFieldTypes::TEXT, 'config' => $this->textFieldConfig('Last Name', 'Nachname', 3)],
                    ['name' => 'school_address', 'type' => CustomFieldTypes::TEXT, 'config' => $this->textFieldConfig('Street Address', 'Straßenadresse', 4)],
                    ['name' => 'school_postal_code', 'type' => CustomFieldTypes::TEXT, 'config' => $this->textFieldConfig('Postal Code', 'Postleitzahl', 5)],
                    ['name' => 'school_city', 'type' => CustomFieldTypes::TEXT, 'config' => $this->textFieldConfig('City', 'Stadt', 6)],
                    [
                        'name' => 'school_country',
                        'type' => CustomFieldTypes::ENTITY,
                        'config' => [
                            'entity' => 'country',
                            'componentName' => 'sw-entity-single-select',
                            'customFieldType' => 'entity',
                            'label' => [
                                'en-GB' => 'Country',
                                'de-DE' => 'Land',
                            ],
                            'customFieldPosition' => 7,
                        ],
                    ],
                ],
            ],
        ];
    }

    private function textFieldConfig(string $enLabel, string $deLabel, int $position): array
    {
        return [
            'componentName' => 'sw-field',
            'customFieldType' => 'text',
            'label' => [
                'en-GB' => $enLabel,
                'de-DE' => $deLabel,
            ],
            'customFieldPosition' => $position,
        ];
    }

    private function checkboxFieldConfig(string $enLabel, string $deLabel, int $position): array
    {
        return [
            'componentName' => 'sw-field',
            'customFieldType' => 'checkbox',
            'label' => [
                'en-GB' => $enLabel,
                'de-DE' => $deLabel,
            ],
            'customFieldPosition' => $position,
        ];
    }

    public function getAllCustomFieldSetIds(Context $context): array
    {
        $fieldsets = [self::CUSTOM_FIELDSET_NAME, self::SCHOOL_SHIPPING_INFORMATION_NAME];
        $ids = [];

        foreach ($fieldsets as $name) {
            $ids = array_merge($ids, $this->getCustomFieldSetIds($name, $context));
        }

        return $ids;
    }
}
