<script setup lang="ts">
import {onMounted, ref} from 'vue'
import {api} from "../utilities/api";
import {addNotification} from "../utilities/notifications";
import {getTemplates} from "../utilities/templates";
import {apiKey, getApiKey, updateApiToken} from "../utilities/api-token";

onMounted(() => {
  getApiKey();
})

</script>

<template>

  <div>

    <div>
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-gray-900">API Token</h3>
            <p class="mt-1 text-sm text-gray-600">Connect the plugin with your mediamask.io account to start using the
              plugin.</p>
          </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
          <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
              <div class="grid grid-cols-3 gap-6">
                <div class="col-span-3 sm:col-span-2">
                  <label for="company-website" class="block text-sm font-semibold text-gray-700">API Key</label>
                  <div class="mt-1 flex rounded-md shadow-sm">
                    <input
                        v-model="apiKey"
                        type="text" name="company-website" id="company-website"
                        class="focus:ring-blue-500 focus:border-blue-500 flex-1 px-3 py-2 block w-full rounded-md sm:text-sm border-gray-300"
                        placeholder="XXXX-XXXX-XXXX-XXXX"/>

                  </div>
                  <span class="text-gray-500 inline-block pt-2">To get an API Key you need to register at <a
                      class="text-blue-500" href="https://mediamask.io">mediamask.io</a>. You can generate a new API Token at <a
                      href="https://mediamask.io/team/api-tokens" class="text-blue-500">https://mediamask.io/team/api-tokens</a></span>
                </div>

              </div>

            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
              <button
                  @click="updateApiToken()"
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
