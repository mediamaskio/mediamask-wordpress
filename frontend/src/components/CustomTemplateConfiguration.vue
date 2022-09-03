<script setup lang="ts">
import { onMounted, ref} from 'vue'
import {api} from "../utilities/api";
import {addNotification} from "../utilities/notifications";
import { Template, TemplateRuleConfiguration} from "../../types";
import TemplateConfiguration from "./TemplateConfiguration.vue";
import {getTemplates} from "../utilities/templates";

onMounted(() => {
  getTemplates();
  getCustomConfig();
})

function saveCustomConfig(){
  const requestBody = Object.assign({}, customTemplateConfigurations.value);

  console.log(requestBody);

  api.post('mediamask/v1/custom-configuration', requestBody)
      .then(function (response) {
        addNotification();
        console.log(response);
      })
      .catch(function (error) {
        console.log(error);
      });
}

function getCustomConfig() {
  api.get('mediamask/v1/custom-configuration')
      .then(function (response) {
        customTemplateConfigurations.value = response.data.custom_configuration.map((config) => {
            if(Array.isArray(config.dynamic_layers)){
              config.dynamic_layers = {}
            }
            return config;

        });
        // Object.assign(customTemplateConfigurations, response.data.custom_configuration) // equivalent to reassign
      })
      .catch(function (error) {
        console.log(error);
      });
}

const customTemplateConfigurations = ref<Array<TemplateRuleConfiguration>>([]);

function addEmptyCustomConfig(){
  customTemplateConfigurations.value.push({
    'mediamask_template_id': null,
    'post_type': 'post',
    'template': 'default',
    'dynamic_layers': {}
  })
}

</script>

<template>
  <div>
    <div>
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Custom Templates</h3>
            <p class="mt-1 text-sm text-gray-600">You can configure custom templates for custom post types, templates, archive pages etc.</p>

            <button
                @click="addEmptyCustomConfig"
                v-if="customTemplateConfigurations.length > 0"
                type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base mt-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="-ml-1 mr-3 h-5 w-5" >
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
              Add Custom Template Rule
            </button>
          </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
          <div class="w-full flex justify-center">

            <div class="text-center"
                 v-if="customTemplateConfigurations.length === 0"
            >
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No custom templates rules</h3>
              <p class="mt-1 text-sm text-gray-500 max-w-xs">Go ahead and create custom rules for your post types, page templates etc.</p>
              <div class="mt-6">
                <hr>
                <button
                    @click="addEmptyCustomConfig"
                    type="button" class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="-ml-1 mr-3 h-5 w-5" >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                  </svg>
                  New Custom Template Rule
                </button>
              </div>
            </div>
          </div>
          <div v-for="(customTemplateConfiguration, i) in customTemplateConfigurations" :key="i" class="shadow mb-3 sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <TemplateConfiguration @removeRule="customTemplateConfigurations = customTemplateConfigurations.splice(i, 1)" v-model="customTemplateConfigurations[i]"></TemplateConfiguration>
            </div>
          </div>
          <div class="flex justify-end pt-6">
            <button
                @click="saveCustomConfig"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Save
            </button>

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
