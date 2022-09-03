
export interface DynamicLayer {
    id: String
    index: number
    name: String
    type: String
}

export type Template = {
    name: String | null
    id: String | null
    dynamic_layers?: DynamicLayer[]
}

export type TemplateConfiguration = {
    mediamask_template_id: String | null
    dynamic_layers?: DynamicLayer[]
}

export type TemplateRuleConfiguration = {
    mediamask_template_id: String | null
    post_type: String | null
    template: String | null
    dynamic_layers: unknown
}

export interface DynamicLayerConfiguration {
    values: {
        [key: string]: string
    }
}

