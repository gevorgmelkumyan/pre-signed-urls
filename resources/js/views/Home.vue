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

const files = ref([])

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
			fileSent('uploaded')
		})
		.catch(error => {
			loadingButton.value = false
            fileSent('failed')
            console.error(error)
		})
}

const fileSent = (status) => {
	loadingButton.value = true
	axios
		.put(`/api/files/${fileId.value}/update-file-status`, {
            status
		})
		.then(response => {
			loadingButton.value = false
			reset()
		})
		.catch(error => {
			loadingButton.value = false
			console.error(error)
		})
}

const reset = () => {
	presignedUrl.value = null
	file.value = 0
	fileId.value = null
    files.value = []
	getFiles()
}

</script>

<template>
    <h1 class="text-center">Presigned URL File Upload</h1>
    <v-container class="mt-5">
        <v-row>
            <v-col cols="8">
                <v-file-input
	                label="Drop file here"
	                @change="fileUploaded"
                    v-model="files"
                />
            </v-col>
	        <v-col cols="4">
		        <v-btn
			        @click="uploadToS3"
			        color="primary"
			        :loading="loadingButton"
			        :disabled="!presignedUrl"
		        >
			        Upload
		        </v-btn>
	        </v-col>
        </v-row>

	    <v-row v-if="presignedUrl">
		    <v-col cols="12">
			    <p class="mt-4">Presigned URL: {{ presignedUrl }}</p>
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
						        Date
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
							        v-if="file.status === 'created'"
							        color="primary"
						        >
							        CREATED
						        </v-chip>
						        <v-chip
							        v-else-if="file.status === 'uploaded'"
							        color="green"
						        >
							        UPLOADED
						        </v-chip>
						        <v-chip
							        v-else
							        color="red"
						        >
							        FAILED
						        </v-chip>
					        </td>
					        <td>{{ file.date }}</td>
					        <td>{{ file.path }}</td>
					        <td>
						        <a v-if="file.status === 'uploaded'" :href="file.url" target="_blank">
							        Download
						        </a>
						        <template v-else>
							        -
						        </template>
					        </td>
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
