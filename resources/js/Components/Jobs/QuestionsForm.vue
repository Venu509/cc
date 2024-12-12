<template>
    <div class="flex flex-col gap-6">
        <div class="card">
            <div class="p-6">
                <div>
                    <div class="grid lg:grid-cols-1 gap-3" v-for="(question, index) in job.questions" :key="index">
                        <div>
                            <Label :required="true">{{ question.question }}</Label>
                            <Input
                                :id="question.id"
                                :name="question.id"
                                type="text"
                                v-model="answers[question.id]"
                                @input="validateField(question.id)"
                                required
                            />
                            <div v-if="errors[question.id]" class="text-red-600 text-sm mt-1">
                                {{ errors[question.id] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex mt-4 float-right">
                    <Button @click="submitAnswers">
                        Apply
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineEmits, onMounted } from 'vue';
import Button from "@/Components/Button.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";

const props = defineProps({
    job: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(["submitAnswer"]);

const answers = ref({});
const errors = ref({});

onMounted(() => {
    if (props.job && props.job.questions) {
        props.job.questions.forEach(question => {
            answers.value[question.id] = '';
        });
    }
});

const validateField = (id) => {
    if (!answers.value[id] || answers.value[id].trim() === "") {
        errors.value[id] = "This field is mandatory.";
    } else {
        errors.value[id] = null;
    }
};

const validateForm = () => {
    let isValid = true;

    props.job.questions.forEach(question => {
        if (!answers.value[question.id] || answers.value[question.id].trim() === "") {
            errors.value[question.id] = "This field is mandatory.";
            isValid = false;
        } else {
            errors.value[question.id] = null;
        }
    });

    return isValid;
};

const submitAnswers = () => {
    if (!validateForm()) {
        return;
    }

    const formattedAnswers = Object.keys(answers.value).map(id => ({
        id: id,
        answer: answers.value[id]
    }));

    const newJob = {
        ...props.job,
        answers: formattedAnswers
    };

    emit('submitAnswer', newJob);
};
</script>
