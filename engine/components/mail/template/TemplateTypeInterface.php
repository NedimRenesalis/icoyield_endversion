<?php

namespace app\components\mail\template;

/**
 * Interface for filling out mail templates
 */
interface TemplateTypeInterface
{
    /**
     * TemplateTypeInterface constructor.
     * @param array $data
     */
    public function __construct(array $data);

    /**
     * Populate and return list of vars by related data
     *
     * @return mixed
     */
    public function populate();

    /**
     * Email of recipient
     *
     * @return mixed
     */
    public function getRecipient();
}
