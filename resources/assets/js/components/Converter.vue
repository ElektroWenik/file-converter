<template>
    <v-app id="inspire">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Converter</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-card-text>
                                <div>
                                    <v-btn
                                           color="info"
                                           block
                                           @click="$upload.select('sourceFile')"
                                           :disabled="$upload.meta('sourceFile').status === 'sending'">

                                        <span v-show="$upload.meta('sourceFile').status === 'sending'">Converting...</span>
                                        <span v-show="$upload.meta('sourceFile').status !== 'sending'">Select File For Convert</span>
                                    </v-btn>

                                    <p v-if="sourceFileSelected" class="text-xs-center headline">
                                        {{ sourceFile.name }}
                                    </p>

                                </div>
                                <div>
                                    <v-select
                                            :items="outputFileExtensionList"
                                            v-model="outputFileExtension"
                                            label="Extension for output file"
                                            single-line
                                            required
                                            :error-messages="errors.collect('Extension')"
                                            v-validate="'required'"
                                            data-vv-name="Extension"
                                    ></v-select>
                                </div>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn download v-if="convertedFileUrl" :href="convertedFileUrl" color="orange darken-1">Download Converted File</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" @click="sendFile">Convert</v-btn>
                            </v-card-actions>
                        </v-card>
                        <v-alert
                                dismissible
                                v-if="sourceFileHasErrors"
                                type="error"
                                v-bind:key="index"
                                v-for="(error, index) in sourceFileErrors"
                                :value="true"
                        >
                            {{ error }}
                        </v-alert>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        $_veeValidate: {
            validator: 'new'
        },
        data: () => ({
            allowedExtensions: ['json', 'xml', 'yml', 'csv'],
            sourceFileExtension: '',
            outputFileExtension: '',
            convertedFileUrl: false
        }),
        computed: {
            sourceFile() { return this.$upload.files('sourceFile').all[0]; },
            sourceFileSelected() { return !!this.$upload.files('sourceFile').all.length; },
            sourceFileHasErrors() { return !!this.$upload.files('sourceFile').error.length; },
            sourceFileErrors() {
                return this.$upload.files('sourceFile').error.reduce((acc, value) => {
                    if(value.errors.length)
                       value.errors.map(error => acc.push(error.message));
                    return acc
                }, []);
            },
            outputFileExtensionList() { return this.allowedExtensions.filter(ext => ext !== this.sourceFileExtension); }
        },
        created: function(){
            this.$upload.new('sourceFile', {
                url: '/convertFile',
                startOnSelect: false,
                extensions: this.allowedExtensions,
                maxFiles: 1,
                http: this._http,
                parseErrors: this._parseErrors,
                onSuccess(response){
                    this.convertedFileUrl = response.data.fileUrl;
                },
                onError(err){
                    if(err) {
                        alert(err);
                        console.log(err);
                    }
                },
                onSelect(files){
                    if(this.sourceFileSelected) {
                        this.sourceFileExtension = this.sourceFile.name.split('.').pop();
                        this.outputFileExtension = this.outputFileExtension === this.sourceFileExtension
                                                    ? this.outputFileExtensionList[0]
                                                    : this.outputFileExtension;
                    }
                }
            });

        },
        methods: {
            sendFile(){
               this.$validator.validateAll()
                   .then(valid => {
                       if(valid)
                           this.$upload.start('sourceFile');
                       this.$upload.reset('sourceFile')
                   }).catch(err => alert(err));

            },
            _http(data) {
                data.body.append('outputFileExtension', this.outputFileExtension);
                data.body.append('sourceFileExtension', this.sourceFileExtension);

                this.axios.post(data.url, data.body, { progress: data.progress }).then(data.success, data.error);
            },
            _parseErrors(error) {
                return error.response.data.detail ? [{
                    rule: 'fileError',
                    message: error.response.data
                }] : [];
            }
        }
    }
</script>