<script setup>
import { VBtn } from "vuetify/components"
import { VContainer, VRow, VCol, VFileInput, VTable } from "vuetify/components"
import {ref} from "vue"

const loading = ref(false)
const presignedUrl = ref(null)
const uploadedFiles = ref([])
</script>

<template>
    <h1>Presigned URL File Upload</h1>
    <v-container>
        <v-row>
            <v-col cols="12">
                <v-file-input label="Drop file here" />
                <v-btn color="primary">Upload</v-btn>
                <p v-if="presignedUrl">Presigned URL: {{ presignedUrl }}</p>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <v-table>
                    <thead>
                    <tr>
                        <th class="text-left">
                            Name
                        </th>
                        <th class="text-left">
                            Status
                        </th>
                        <th class="text-left">
                            Path in S3
                        </th>
                        <th class="text-left">
                            Download URL
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="file in uploadedFiles"
                        :key="file.id"
                    >
                        <td>{{ file.name }}</td>
                        <td>{{ file.status }}</td>
                        <td>{{ file.path }}</td>
                        <td><a :href="file.url">Download</a></td>
                    </tr>
                    </tbody>
                </v-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>

</style>
