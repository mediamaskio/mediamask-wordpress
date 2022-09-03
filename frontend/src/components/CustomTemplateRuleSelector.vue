<script setup lang="ts">
import {computed, onMounted, watch, watchEffect} from 'vue'
import {Template} from "../../types";

import {useVModel} from "@vueuse/core";

const props = defineProps<{
  postType: string
  template: string
}>()
const emit = defineEmits(['update:postType', 'update:template'])

const data = useVModel(props, 'postType', emit)
const template = useVModel(props, 'template', emit)

const postTypesAndTaxonomiesy = window.wpApiSettings.post_types_and_taxonomies;

const templates = computed(() => {
  return postTypesAndTaxonomiesy.find((postType) => postType.id === data.value)?.templates
})

</script>

<template>
  <div>
    <h3 class="block text-sm font-medium text-gray-700">Template Rule</h3>
    <p class="text-gray-700 pb-4 pt-1">
      Configure when a mediamask template will be used by rules of post types and page templates.
    </p>
      <div class="grid grid-cols-2 gap-6">

        <div>
          <label for="templates" class="block text-sm font-medium text-gray-700">Choose Post Type/Taxonomy</label>
          <select v-model="data" id="templates" name="location"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            <option v-for="postType in postTypesAndTaxonomiesy" :key="postType.id" :value="postType.id">{{ postType.id }}
            </option>
          </select>
        </div>
        <div>
          <label for="templates" class="block text-sm font-medium text-gray-700">Choose Wordpress Template</label>
          <select v-model="template" id="templates" name="location"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            <option v-for="template in templates" :key="template" :value="template">{{ template }}</option>
          </select>
        </div>
      </div>
    </div>

</template>
