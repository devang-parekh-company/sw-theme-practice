<?php

namespace SchoolWiseCommerceControls\Core\Content\Extension;

use SchoolWiseCommerceControls\Core\Content\SchoolLicenceInformation\SchoolLicencesInformationDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CategoryExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToOneAssociationField(
                'categoryId',
                'id',
                'category_id',
                SchoolLicencesInformationDefinition::class,
                false
            )
        );
    }

    public function getDefinitionClass(): string
    {
        return CategoryDefinition::class;
    }
}