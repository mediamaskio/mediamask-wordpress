<script setup lang="ts">
import {computed, onBeforeMount, onMounted, reactive, ref, watch} from 'vue'
import {api} from "../utilities/api";
import DynamicTextLayerSelector from '../components/DynamicTextLayerSelector.vue'
import {addNotification} from "../utilities/notifications";
import DynamicImageLayerSelector from "./DynamicImageLayerSelector.vue";
import {DynamicLayerConfiguration, Template, TemplateConfiguration, TemplateRuleConfiguration} from "../../types";
import { useVModel } from "@vueuse/core";
import CustomTemplateRuleSelector from "./CustomTemplateRuleSelector.vue";
import {templates} from "../utilities/templates";

const props = defineProps<{
  modelValue: TemplateRuleConfiguration
}>()
const emit = defineEmits(['update:modelValue', 'removeRule'])

const data = useVModel(props, 'modelValue', emit)

const dynamicLayers = computed(() => {
  return templates.value?.find((template) => template.id === data.value.mediamask_template_id)?.dynamic_layers
})

const templateLink = computed(() => {
  return 'https://mediamask.io/templates/' + data.value.mediamask_template_id;
})

onBeforeMount( () => {
  if(data.value.mediamask_template_id === null){
    if(templates.value && templates.value.length > 0){
      data.value.mediamask_template_id = templates.value[0]?.id
    }
  }
})

function removeRule(){
  emit('removeRule');
}
function resetDynamicLayers(){
  data.value.dynamic_layers = {};
}


</script>

<template>
  <div class="relative">
    <svg
        @click="removeRule"
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute hover:text-gray-400 cursor-pointer top-0 right-0 w-6 h-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <CustomTemplateRuleSelector v-model:postType="data.post_type" v-model:template="data.template"></CustomTemplateRuleSelector>
    <hr class="border-t border-gray-200 mt-4">

    <div class="grid grid-cols-2 gap-6 pt-6">

      <div class="">
        <div>
          <label for="templates" class="block text-sm font-medium text-gray-700">Choose Mediamask Template</label>
          <select
              @change="resetDynamicLayers"
              v-model="data.mediamask_template_id" id="templates" name="location"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            <option v-for="template in templates" :value="template.id" :key="template.id">{{
                template.name
              }}
            </option>
          </select>
        </div>


        <span class="text-gray-500 inline-block pt-2">You can create templates at <a
            class="text-blue-500" href="https://mediamask.io">mediamask.io</a>.</span>
      </div>
    </div>
    <hr class="my-4">
    <div>
      <h3 class="block text-sm font-medium text-gray-700">Dynamic Layers</h3>
      <p class="text-gray-700 pb-4 pt-1">
        Each dynamic layer can display one text or image from your Wordpress Post/Page etc. <br>
        You can edit the names of dynamic layers in <a class="text-blue-500" target="_blank"
                                                       :href="templateLink">here</a>.
      </p>

      <div v-for="dynamicLayer in dynamicLayers"
           class="rounded-lg w-full py-3 my-2 px-4 border-gray-300 border grid grid-cols-2 items-center gap-3">
        <div>
          <div class="py-1">
            <span class="text-sm font-medium">Name: </span> {{ dynamicLayer.name }}
          </div>
          <div class="py-1">
            <span class="text-sm font-medium">Type: </span> {{ dynamicLayer.type }}
          </div>
        </div>
        <DynamicTextLayerSelector v-model="data.dynamic_layers[dynamicLayer.name]"
                                  v-if="dynamicLayer.type === 'text'"></DynamicTextLayerSelector>
        <DynamicImageLayerSelector v-model="data.dynamic_layers[dynamicLayer.name]"
                                   v-if="dynamicLayer.type === 'image'"></DynamicImageLayerSelector>
      </div>
    </div>
  </div>
</template>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>
