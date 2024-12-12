<template>
  <div class="flex flex-col gap-6">
    <div class="card">
      <div class="p-6">
        <div class="grid lg:grid-cols-2 gap-3">
          <div>
            <Label :required="true">Role</Label>
            <Multiselect
                v-model="form.role"
                mode="single"
                class="mt-1"
                :hide-selected="true"
                :close-on-select="true"
                :searchable="true"
                :options="roles"
                :loading="isLoading"
                :object="true"
            />
            <InputError :message="form.errors.role" class="mt-2"/>
          </div>

          <div>
            <Label :required="true">Login Via</Label>
            <Select id="via" name="via" v-model="form.via" @change="loginVia" class="w-full">
              <option value="phone" selected>Phone</option>
              <option value="email">Email</option>
            </Select>
          </div>

          <div>
            <Label :required="true">Name</Label>
            <Input
                id="name"
                v-model="form.name"
                autocomplete="name"
                name="name"
                type="text"
            />
            <InputError :message="form.errors.name" class="mt-2"/>
          </div>

          <div v-show="!isLoginViaMobile">
            <Label :required="true">Email</Label>
            <Input
                id="email"
                v-model="form.email"
                autocomplete="email"
                name="email"
                type="text"
            />
            <InputError :message="form.errors.email" class="mt-2"/>
          </div>

          <div v-show="isLoginViaMobile">
            <Label :required="true">Phone</Label>
            <Input
                id="phone"
                v-model="form.phone"
                autocomplete="phone"
                name="phone"
                type="text"
            />
            <InputError :message="form.errors.phone" class="mt-2"/>
          </div>

          <div>
            <Label :required="true" for="image">Image</Label>
            <FileInput
                type="file"
                accept="image/x-png,image/gif,image/jpeg"
                class="mt-1 block w-full"
                name="avatar"
                @change="handleImageUpload"
            />
            <InputError :message="form.errors.avatar" class="mt-2"/>
          </div>

          <div>
            <Label>Preview</Label>
            <img
                v-if="form.imagePreview"
                :src="form.imagePreview"
                class="w-2/5"
                style="background-color: black;"
            />
          </div>
        </div>

        <div class="flex mt-4">
          <Button
              :disabled="isSaving"
              @click="save"
          >
            {{ buttonText }}
          </Button>
        </div>
      </div>
    </div>
  </div>

</template>

<script setup>
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Button from "@/Components/Button.vue";
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import InputError from "@/Components/InputError.vue";
import FileInput from "@/Components/FileInput.vue";
import Multiselect from "@vueform/multiselect";
import Toggle from "@/Components/Toggle.vue";
import Select from "@/Components/Select.vue";

let props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
});

const form = useForm({...props.modelValue});

let isLoading = ref(true)
const isSaving = ref(false);
const isLoginViaMobile = ref(true);
let roles = ref({})

const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return props.isEditing ? 'Update' : 'Save';
});

if(props.isEditing) {
  isLoginViaMobile.value = form.via === 'phone'
}

getRoles()

function getRoles() {
  isLoading.value = true;
  axios
      .get(route("roles.fetch"), {
        params: {
          ignore: [
            'master',
            'super-admin',
            'admin',
            'outsource',
          ]
        },
      })
      .then((response) => {
        roles.value = response.data.roles;
        isLoading.value = false;
      });
}

function handleImageUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.avatar = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      form.imagePreview = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function save() {
  isSaving.value = true;

  if (props.isEditing) {
    Inertia.post(route("users.update", props.modelValue.id), form, {
      onError: (errors) => {
        form.clearErrors().setError(errors);
        isSaving.value = false;
      },
      onSuccess: () => {
        form.reset();
        isSaving.value = false;
      },
    });
    return;
  }
  Inertia.post(route("users.store"), form, {
    onError: (errors) => {
      form.clearErrors().setError(errors);
      isSaving.value = false;
    },
    onSuccess: () => {
      form.reset();
      isSaving.value = false;
    },
  });
}

function loginVia(event) {
  isLoginViaMobile.value = (event.target.value === 'phone')
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
