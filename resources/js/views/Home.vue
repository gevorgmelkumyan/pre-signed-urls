<script setup>
import axios from "axios"
import { VBtn } from "vuetify/components"
import { VContainer, VRow, VCol, VFileInput, VTable, VChip, VSkeletonLoader } from "vuetify/components"
import {onBeforeMount, ref} from "vue"

const loading = ref(false)
const loadingButton = ref(false)
const presignedUrl = ref(null)
const uploadedFiles = ref([])

const file = ref(0)
const fileId = ref(null)

onBeforeMount(() => {
	getFiles()
})

const getFiles = () => {
	loading.value = true
	axios
		.get('/api/files')
		.then(response => {
			loading.value = false
			uploadedFiles.value = response.data
		})
		.catch(error => {
			loading.value = false
			console.error(error)
		})
}

const fileUploaded = (event) => {
	file.value = event.target.files[0]

	getPreSignedUrl()
}

const getPreSignedUrl = () => {
	loading.value = true
	axios
		.get(`/api/presigned-url?file_name=${file.value.name}&file_type=${file.value.type}`)
		.then(response => {
			loadingButton.value = false
			fileId.value = response.data.file_id
			presignedUrl.value = response.data.url

			getFiles()
		})
		.catch(error => {
			loadingButton.value = false
			console.error(error)
		})
}

const uploadToS3 = () => {
	loadingButton.value = true
	axios
		.put(presignedUrl.value, file.value, {
			headers: {
				'Content-Type': file.value.type
			}
		})
		.then(response => {
			loadingButton.value = false
			fileSent()
		})
		.catch(error => {
			loadingButton.value = false
			console.error(error)
		})
}

const fileSent = () => {
	loadingButton.value = true
	axios
		.put(`/api/files/${fileId.value}/uploaded`, {
			file_id: fileId.value
		})
		.then(response => {
			loadingButton.value = false
			getFiles()
		})
		.catch(error => {
			loadingButton.value = false
			console.error(error)
		})
}

</script>

<template>
    <h1 class="text-center">Presigned URL File Upload</h1>
    <v-container class="mt-5">
        <v-row>
            <v-col cols="12">
                <v-file-input label="Drop file here" @change="fileUploaded" />
                <v-btn @click="uploadToS3" color="primary" :loading="loadingButton">Upload</v-btn>
                <p v-if="presignedUrl" class="mt-4">Presigned URL: {{ presignedUrl }}</p>
            </v-col>
        </v-row>

        <v-row class="mt-5">
	        <v-skeleton-loader :loading="loading" type="table">
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
					        <td>
						        <v-chip
							        v-if="file.status === 'uploading'"
							        color="primary"
						        >
							        UPLOADING
						        </v-chip>
						        <v-chip
							        v-else
							        color="green"
						        >
							        UPLOADED
						        </v-chip>
					        </td>
					        <td>{{ file.path }}</td>
					        <td><a :href="file.url" target="_blank">Download</a></td>
				        </tr>
				        </tbody>
			        </v-table>
		        </v-col>
	        </v-skeleton-loader>
        </v-row>
    </v-container>
</template>

<style scoped>

</style>
