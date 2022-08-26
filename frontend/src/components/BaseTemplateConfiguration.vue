<script setup lang="ts">
import {computed, onMounted, reactive, ref, watch} from 'vue'
import {api} from "../utilities/api";
import DynamicTextLayerSelector from '../components/DynamicTextLayerSelector.vue'
import {addNotification} from "../utilities/notifications";
import DynamicImageLayerSelector from "./DynamicImageLayerSelector.vue";

interface DynamicLayer {
  id: string
  index: number
  name: string
  type: string
}

type Template = {
  id: string
  name: string
  dynamic_layers?: DynamicLayer[]
}

const templates = ref<Template[] | null>(null);

const selectedTemplateId = ref();

// interface DynamicLayerConfiguration {
//   id: string,
//   value: string
// }

interface DynamicLayerConfiguration {
  values: {
    [key: string]: string
  }
}

const baseConfiguration = ref<DynamicLayerConfiguration>({values: {}})
const newDynamicLayerConfiguration = reactive<DynamicLayerConfiguration>({values: {}})

const selectedTemplate = computed(() => {
  return templates.value ? templates.value.find((template) => template.id === selectedTemplateId.value) : null
})

onMounted(() => {
  getTemplates();
  getBaseConfig();
})

const dynamicLayers = computed(() => {
  return selectedTemplate.value?.dynamic_layers
})

const templateLink = computed(() => {
  return 'https://mediamask.io/templates/' + selectedTemplateId.value;
})

function getBaseConfig() {
  api.get('mediamask/v1/base-configuration')
      .then(function (response) {

        baseConfiguration.value.values = response.data.base_configuration.values
        selectedTemplateId.value = response.data.base_configuration.template
      })
      .catch(function (error) {
        console.log(error);
      });
}

watch(dynamicLayers, () => {
  newDynamicLayerConfiguration.values = {};
  if(dynamicLayers.value){
    dynamicLayers.value.forEach((dynamicLayer) => {
      if (baseConfiguration.value.values[dynamicLayer.name]) {
        newDynamicLayerConfiguration.values[dynamicLayer.name] = baseConfiguration.value.values[dynamicLayer.name]
      }
    })
  }
})

function saveBaseConfig() {
  api.post('mediamask/v1/base-configuration', {
    'template': selectedTemplateId.value,
    'values': newDynamicLayerConfiguration.values
  })
      .then(function (response) {
        addNotification();

        console.log(response);
      })
      .catch(function (error) {
        console.log(error);
      });
}

function getTemplates() {
  api.get('mediamask/v1/templates')
      .then(function (response) {
        templates.value = response.data.data
        if (selectedTemplateId.value === null) {
          selectedTemplateId.value = templates.value ? templates.value[0].id : null
        }

      })
      .catch(function (error) {
        console.log(error);
      });
}

</script>

<template>

  <div>

    <div>
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Base Template</h3>
            <p class="mt-1 text-sm text-gray-600">This configuration will act as the fallback.</p>
          </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
          <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
              <div class="grid grid-cols-3 gap-6">
                <div class="col-span-3 sm:col-span-2">
                  <div>
                    <label for="templates" class="block text-sm font-medium text-gray-700">Choose Template</label>
                    <select v-model="selectedTemplateId" id="templates" name="location"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
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
              <hr>
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
                  <DynamicTextLayerSelector v-model="newDynamicLayerConfiguration.values[dynamicLayer.name]"
                                            v-if="dynamicLayer.type === 'text'"></DynamicTextLayerSelector>
                  <DynamicImageLayerSelector v-model="newDynamicLayerConfiguration.values[dynamicLayer.name]"
                                             v-if="dynamicLayer.type === 'image'"></DynamicImageLayerSelector>

                </div>
              </div>

            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
              <button
                  @click="saveBaseConfig"
                  class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Save
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="hidden sm:block" aria-hidden="true">
      <div class="py-5">
        <div class="border-t border-gray-200"/>
      </div>
    </div>

  </div>
</template>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>