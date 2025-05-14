<?php declare(strict_types=1);

namespace Blog\DataResolver;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\FieldConfig;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\EntityResolverContext;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\SalesChannel\Struct\ImageStruct;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
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

        if (!$imageConfig || $imageConfig->isMapped()) {
            return null;
        }

        $mediaId = $imageConfig->getStringValue();

        if (empty($mediaId)) {
            return null;
        }

        $criteria = new Criteria([$mediaId]);
        $collection = new CriteriaCollection();
        $collection->add('media_' . $slot->getUniqueIdentifier(), MediaDefinition::class, $criteria);

        return $collection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $config = $slot->getFieldConfig();
        $data = new ArrayEntity();
        $data->setUniqueIdentifier(Uuid::randomHex());
        $slot->setData($data);

        $image = new ImageStruct();
        $imageConfig = $config->get('media');

        if ($imageConfig) {
            $this->addMediaEntity($slot, $image, $result, $imageConfig, $resolverContext);
        }

        $data->set('media', $image);


        $urlConfig = $config->get('url');
        if ($urlConfig) {
            $data->set('url', $urlConfig->getStringValue());
        }
    }

    private function addMediaEntity(
        CmsSlotEntity $slot,
        ImageStruct $image,
        ElementDataCollection $result,
        FieldConfig $config,
        ResolverContext $resolverContext
    ): void {
        if ($config->isMapped() && $resolverContext instanceof EntityResolverContext) {

            $media = $this->resolveEntityValue($resolverContext->getEntity(), $config->getValue());
            if ($media !== null) {
                $image->setMediaId($media->getUniqueIdentifier());
                $image->setMedia($media);
            }
        }

        if ($config->isStatic()) {
            $mediaId = $config->getStringValue();
            $image->setMediaId($mediaId);

            $searchResult = $result->get('media_' . $slot->getUniqueIdentifier());
            if ($searchResult) {

                $media = $searchResult->get($mediaId);
                if ($media !== null) {
                    $image->setMedia($media);
                }
            }
        }
    }
}