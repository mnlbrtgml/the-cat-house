<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const securityQuestion = [
    "What was the street name you lived in as a child?",
    "What primary school did you attend?",
    "In what city or town was your first job?",
    "What was the make and model of your first car?",
    "What is your oldest cousin's first and last name?",
];

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: "PUT",
    user_id: props.user.id,
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    address: props.user.address,
    phone_number: props.user.phone_number,
    age: props.user.age,
    citizenship: props.user.citizenship,
    occupation: props.user.occupation,
    security_question: props.user.security_question,
    security_answer: props.user.security_answer,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    Inertia.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title> Profile Information </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div
                v-if="$page.props.jetstream.managesProfilePhotos"
                class="col-span-6 sm:col-span-4"
            >
                <!-- Profile Photo File Input -->
                <input
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                />

                <InputLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="!photoPreview" class="mt-2">
                    <img
                        :src="user.profile_photo_url"
                        :alt="user.name"
                        class="rounded-full h-20 w-20 object-cover"
                    />
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span
                        class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="
                            'background-image: url(\'' + photoPreview + '\');'
                        "
                    />
                </div>

                <SecondaryButton
                    class="mt-2 mr-2"
                    type="button"
                    @click.prevent="selectNewPhoto"
                >
                    Select A New Photo
                </SecondaryButton>

                <SecondaryButton
                    v-if="user.profile_photo_path"
                    type="button"
                    class="mt-2"
                    @click.prevent="deletePhoto"
                >
                    Remove Photo
                </SecondaryButton>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- First Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="first_name" value="First Name" />
                <TextInput
                    id="first_name"
                    v-model="form.first_name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="first_name"
                />
                <InputError :message="form.errors.first_name" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="last_name" value="Last Name" />
                <TextInput
                    id="last_name"
                    v-model="form.last_name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="last_name"
                />
                <InputError :message="form.errors.last_name" class="mt-2" />
            </div>

            <!-- Address -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="address" value="Address" />
                <TextInput
                    id="address"
                    v-model="form.address"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="address"
                />
                <InputError :message="form.errors.address" class="mt-2" />
            </div>
            <!-- Phone Number -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="phone_number" value="Phone Number" />
                <TextInput
                    id="phone_number"
                    v-model="form.phone_number"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="phone_number"
                />
                <InputError :message="form.errors.phone_number" class="mt-2" />
            </div>
            <!-- Age -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="age" value="Age" />
                <TextInput
                    id="age"
                    v-model="form.age"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="age"
                />
                <InputError :message="form.errors.age" class="mt-2" />
            </div>
            <!-- Citizenship -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="citizenship" value="Citizenship" />
                <TextInput
                    id="citizenship"
                    v-model="form.citizenship"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="citizenship"
                />
                <InputError :message="form.errors.citizenship" class="mt-2" />
            </div>
            <!-- Security Questoiin -->
            <div class="col-span-6 sm:col-span-4">
                <label
                    for="countries"
                    class=" block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Select a security question</label
                >
                <select
                    v-model="form.security_question"
                    id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                    <option
                        v-for="question in securityQuestion"
                        :value="question"
                    >
                        {{ question }}
                    </option>
                </select>
            </div>

            <InputError class="mt-2" :message="form.errors.security_question" />
            <!-- Security Answer -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="security_answer" value="Security Answer" />
                <TextInput
                    id="security_answer"
                    v-model="form.security_answer"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="security_answer"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors.security_answer"
                />
            </div>
            <!-- Occupation -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="occupation" value="Occupation" />
                <TextInput
                    id="occupation"
                    v-model="form.occupation"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="occupation"
                />
                <InputError :message="form.errors.occupation" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.email" class="mt-2" />

                <div
                    v-if="
                        $page.props.jetstream.hasEmailVerification &&
                        user.email_verified_at === null
                    "
                >
                    <p class="text-sm mt-2">
                        Your email address is unverified.

                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-gray-600 hover:text-gray-900"
                            @click.prevent="sendEmailVerification"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div
                        v-show="verificationLinkSent"
                        class="mt-2 font-medium text-sm text-green-600"
                    >
                        A new verification link has been sent to your email
                        address.
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
