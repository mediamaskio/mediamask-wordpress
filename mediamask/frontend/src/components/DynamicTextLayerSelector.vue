<template>
  <div>
    <label for="templates" class="block text-xs font-medium text-gray-700">Choose dynamic text value</label>
    <select v-model="data" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
      <option :value="undefined">No Text</option>
      <option value="title">Title (the_title())</option>
      <option value="description">Excerpt (the_excerpt())</option>
      <option value="publish_date">Published Date (the_date())</option>
      <option value="author_name">Author Name (the_author())</option>
      <option value="permalink">Permalink (the_permalink())</option>
    </select>
  </div>
</template>

<script setup lang="ts">
import { useVModel } from "@vueuse/core";

const props = defineProps<{
  modelValue: string
}>()
const emit = defineEmits(['update:modelValue'])

const data = useVModel(props, 'modelValue', emit)

</script>

<style scoped>

</style>