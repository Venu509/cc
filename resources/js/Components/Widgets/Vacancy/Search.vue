<template>
  <div>
    <div class="grid lg:grid-cols-5 gap-3 p-4 bg-gray-100 divide-x-4 divide-solid divide-slate-500">
      <div class="col-span-2">
        <input type="text" placeholder="Job title or keywords" class="p-2 w-full" />
      </div>
      <div class="col-span-2">
        <input type="text" placeholder="Job title or keywords" class="p-2 w-full" />
      </div>
      <div class="col-span-1">
        <div class="flex items-center w-full md:w-auto">
          <button class="bg-orange-500 text-white rounded-md p-2 px-4">Search</button>
        </div>
      </div>
    </div>

    <div class="divide-y-4 divide-solid divide-slate-500"></div>

    <div class="flex flex-col md:flex-row items-center justify-between p-4 bg-gray-50">
      <div class="flex space-x-2 mt-4 md:mt-0">
        <button id="dropdownBgHoverButton" data-dropdown-toggle="dropdownBgHover" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Dropdown checkbox <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
        </button>
        <select class="border rounded-md p-2">
          <option>Modality</option>
          <option>Remote</option>
          <option>On-Site</option>
        </select>
        <select class="border rounded-md p-2">
          <option>Country</option>
          <option>Sri Lanka</option>
          <option>Kuwait</option>
        </select>
        <select class="border rounded-md p-2">
          <option>Salary</option>
          <option>$1000-$2000</option>
          <option>$2000-$3000</option>
        </select>
      </div>
    </div>

    <div id="dropdownBgHover" class="z-10 hidden w-48 bg-white rounded-lg shadow dark:bg-gray-700">
      <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownBgHoverButton">
        <li>
          <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
            <input id="checkbox-item-4" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
            <label for="checkbox-item-4" class="w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Default checkbox</label>
          </div>
        </li>
        <li>
          <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
            <input checked id="checkbox-item-5" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
            <label for="checkbox-item-5" class="w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Checked state</label>
          </div>
        </li>
        <li>
          <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
            <input id="checkbox-item-6" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
            <label for="checkbox-item-6" class="w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Default checkbox</label>
          </div>
        </li>
      </ul>
    </div>
  </div>

</template>

<script setup>
import {validateField} from "@/Components/Services/Validation";
import Label from "@/Components/Label.vue";
import InputError from "@/Components/InputError.vue";
import Input from "@/Components/Input.vue";
</script>
