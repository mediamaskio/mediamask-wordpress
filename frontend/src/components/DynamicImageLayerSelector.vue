<template>
  <div>
    <label for="templates" class="block text-xs font-medium text-gray-700">Choose dynamic image value</label>
    <select v-model="data" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
      <option :value="undefined">No Image</option>
      <option value="post_thumbnail">Post Thumbnail (the_post_thumbnail())</option>
      <option value="author_image">Author Image</option>
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