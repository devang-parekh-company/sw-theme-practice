<?php

declare(strict_types=1);

namespace CmsTheme\DataResolver;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

use Shopware\Core\Content\Cms\DataResolver\FieldConfig;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\EntityResolverContext;
use Shopware\Core\Content\Cms\SalesChannel\Struct\ImageStruct;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\Framework\Uuid\Uuid;

class CustomImageCmsElementResolver extends AbstractCmsElementResolver
{
    public function getType(): string
    {
        return 'custom-image';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $config = $slot->getFieldConfig();
        $imageConfig = $config->get('media');
        $backgroundImageConfig = $config->get('mobileMedia');

        $ids = [];
        if (!$imageConfig || $imageConfig->isMapped() || $imageConfig->getValue() === null) {
            //
        } else {
            array_push($ids, $imageConfig->getValue());
        }

        if (!$backgroundImageConfig || $backgroundImageConfig->isMapped() || $backgroundImageConfig->getValue() === null) {
            //
        } else {
            array_push($ids, $backgroundImageConfig->getValue());
        }

        if (count($ids) === 0) {
            return null;
        }

        $criteria = new Criteria($ids);

        $criteriaCollection = new CriteriaCollection();
        $criteriaCollection->add('media_' . $slot->getUniqueIdentifier(), MediaDefinition::class, $criteria);

        return $criteriaCollection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $config = $slot->getFieldConfig();
        $data = new ArrayEntity();
        $data->setUniqueIdentifier(Uuid::randomHex());
        $slot->setData($data);

        $image = new ImageStruct();
        $backgroundImage = new ImageStruct();

        $imageConfig = $config->get('media');
        $backgroundImageConfig = $config->get('mobileMedia');

        if ($imageConfig && $imageConfig->getValue()) {
            $this->addMediaEntity($slot, $image, $result, $imageConfig, $resolverContext);
        }
        $data->set('media', $image);

        if ($backgroundImageConfig && $backgroundImageConfig->getValue()) {
            $this->addMediaEntity($slot, $backgroundImage, $result, $backgroundImageConfig, $resolverContext);
        }
        $data->set('mobileMedia', $backgroundImage);
    }
    private function addMediaEntity(
        CmsSlotEntity $slot,
        ImageStruct $image,
        ElementDataCollection $result,
        FieldConfig   $config,
        ResolverContext $resolverContext
    ): void {
        if ($config->isMapped() && $resolverContext instanceof EntityResolverContext) {
            /** @var MediaEntity|null $media */
            $media = $this->resolveEntityValue($resolverContext->getEntity(), $config->getValue());

            if ($media !== null) {
                $image->setMediaId($media->getUniqueIdentifier());
                $image->setMedia($media);
            }
        }

        if ($config->isStatic()) {
            $image->setMediaId($config->getValue());

            $searchResult = $result->get('media_' . $slot->getUniqueIdentifier());
            if (!$searchResult) {
                return;
            }

            /** @var MediaEntity|null $media */
            $media = $searchResult->get($config->getValue());
            if (!$media) {
                return;
            }

            $image->setMedia($media);
        }
    }
}
