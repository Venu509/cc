<template>
  <div class="max-w-md mx-auto text-center px-4 sm:px-8 py-10">
    <header class="mb-8">
      <h1 class="text-2xl font-bold mb-1 capitalize">{{ providerName }} Verification</h1>
      <p class="text-[15px] text-slate-500">
        Enter the {{ digits }}-digit verification code that was sent to your {{ providerName }}.
      </p>

      <p class="text-[15px] text-slate-500">
        Switch <span class="capitalize">{{ via }}</span>
        <button
            @click="emitSwitchProvider"
            class="ml-1 inline-flex items-center text-indigo-500 hover:text-indigo-600 focus:outline-none underline font-medium transition-colors duration-150 ease-in-out"
        >
          {{ currentProvider }}
          <SwitchHorizontalIcon class="w-4 h-4 ml-2" />
        </button>
      </p>
    </header>

    <form @submit.prevent="submitOTP" id="otp-form">
      <div class="flex items-center justify-center gap-3">
        <input
            v-for="(digit, index) in otp"
            :key="index"
            ref="otpInputRefs"
            type="text"
            class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-primary-500 hover:border-primary-800 appearance-none rounded p-4 outline-none focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
            pattern="\d*"
            maxlength="1"
            v-model="otp[index]"
            @input="onInput($event, index)"
            @keydown="onKeyDown($event, index)"
            @focus="onFocus"
            @paste="onPaste"
        />
      </div>

      <div class="max-w-[260px] mx-auto mt-4">
        <button
            type="submit"
            :disabled="isSending"
            class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-indigo-500 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150"
        >
          {{ otpVerifyButtonText }}
        </button>
      </div>
    </form>

    <div class="my-2">
      <span :class="[responseClass, 'font-medium']">{{ responseMessage }}</span>
    </div>

    <div class="text-sm text-slate-500 mt-2">
      Didn't receive the code?
      <button
          type="button"
          :class="[resendButtonEnabled ? 'text-indigo-500 hover:text-indigo-600' : 'text-gray-400 cursor-not-allowed', 'font-medium']"
          :disabled="!resendButtonEnabled"
          @click="resendOTP"
      >
        Resend
      </button>
      <span v-if="!resendButtonEnabled" class="ml-2 text-red-500">
        ({{ countdown }} seconds)
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, computed, watch, onMounted, onUnmounted, nextTick  } from 'vue';
import { SwitchHorizontalIcon } from "@heroicons/vue/solid";

const props = defineProps({
  digits: {
    type: Number,
    default: 4,
  },
  via: {
    type: String,
    default: 'phone',
  },
  response: {
    type: Object,
    default: null,
  },
  isSending: {
    type: Boolean,
    default: false,
  },
  form: {
    type: Object,
    default: null,
  },
});

const responseClass = ref('text-green-500');
const currentProvider = ref(null);
const responseMessage = ref(null);

const otp = ref(Array(props.digits).fill(''));
const emit = defineEmits(['otp-submitted', 'switch-provider']);

watch(
    () => props.response,
    () => {
      let data = props.response.data;

      if(!data.status) {
        responseClass.value = 'text-red-500'
      } else {
        responseClass.value = 'text-green-500'
      }
      responseMessage.value = data.message
    }
);


watch(
    () => props.form,
    () => {
      currentProvider.value = props.via === 'email' ? props.form?.email : props.form?.phone
    }, {deep: true}
);

const providerName = computed(() => {
  return props.via;
});

const otpVerifyButtonText = computed(() => {
  return props.isSending ? 'Verifying ...' : 'Verify OTP';
});

const emitSwitchProvider = () => {
  emit('switch-provider');
};

const submitOTP = () => {
  const otpValue = otp.value.join('');
  emit('otp-submitted', otpValue);
};

const otpInputRefs = ref([]);

onMounted(() => {
  otpInputRefs.value = otpInputRefs.value.slice(0, otp.value.length);  // Ensure only needed refs
  nextTick(() => {
    if (otpInputRefs.value[0]) {
      otpInputRefs.value[0].focus();  // Focus on the first input after mounting
    }
  });
});

const onInput = (event, index) => {
  const value = event.target.value;

  otp.value[index] = value.slice(0, 1);  // Ensure one digit per input

  if (value && index < otp.value.length - 1) {
    if (otpInputRefs.value[index + 1]) {
      otpInputRefs.value[index + 1].focus();
    }
  }
};

const onKeyDown = (event, index) => {
  if (event.key === 'Backspace' && !otp.value[index] && index > 0) {
    otp.value[index - 1] = '';
    otpInputRefs.value[index - 1].focus();
  }
  if (event.key === 'ArrowRight' && index < otp.value.length - 1) {
    otpInputRefs.value[index + 1].focus();
  }
  if (event.key === 'ArrowLeft' && index > 0) {
    otpInputRefs.value[index - 1].focus();
  }
};

const onFocus = (event) => {
  event.target.select();
};

const onPaste = (event) => {
  event.preventDefault();
  const text = event.clipboardData.getData('text').slice(0, otp.value.length);
  if (/^[0-9]+$/.test(text)) {
    text.split('').forEach((char, index) => {
      otp.value[index] = char;
    });
    if (otpInputRefs.value[otp.value.length - 1]) {
      otpInputRefs.value[otp.value.length - 1].focus();
    }
  }
};

// Resend button state and countdown logic
const resendButtonEnabled = ref(true);
const countdown = ref(0);
let countdownInterval = null;

const startCountdown = () => {
  countdown.value = 60;
  resendButtonEnabled.value = false;

  countdownInterval = setInterval(() => {
    countdown.value -= 1;
    if (countdown.value <= 0) {
      clearInterval(countdownInterval);
      resendButtonEnabled.value = true;
    }
  }, 1000);
};

const resendOTP = () => {
  if (resendButtonEnabled.value) {
    emit('resend-requested');
    startCountdown();
  }
};

onUnmounted(() => {
  if (countdownInterval) {
    clearInterval(countdownInterval);
  }
});
</script>
