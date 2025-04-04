

function ShowWarningMessage(message) {
    //$("#warninglabel").text(message);
    //$("#alertwarningmessage").removeClass('hidden');
    //CloseAlertDelay();



    toastr.warning(message);
    $('#toast-container').addClass('show-toast');

}

function ShowSuccessMessage(message) {
    //$("#successlabel").text(message);
    //$("#alertsuccessmessage").removeClass('hidden');
    //CloseAlertDelay();

    toastr.success(message);
    $('#toast-container').addClass('show-toast');

}

function ShowInfoMessage(message) {

    toastr.info(message);
    $('#toast-container').addClass('show-toast');
}

function ShowErrorMessage(message) {

    toastr.error(message);
    $('#toast-container').addClass('show-toast');
}

function CloseAlertDelay() {
    var delay = 3000;
    setTimeout(function () {
        $("#alertwarningmessage").addClass('hidden');
        $("#alertsuccessmessage").addClass('hidden');
    }, delay);
}

$(function () {
    $('.toast-success').addClass('toast-opacity');

});

let ProductTypes = {
    Ashp: 1,
    Boiler: 2,
    ElectricCarCharger: 3,
    SolarPvBattery: 4,
    SolarThermal: 5,
    Battery: 6
};

let UserTypes = {
    SuperAdmin: 1000,
    Trader: 2000,
    Staff: 3000,
    Client: 4000,
    TraderMember: 5000
};

let _app = (function () {
    const app_date_format = "DD/MM/YYYY";
    const system_date_format = "YYYY-MM-DD";


    function dateToAppFormat(date) {
        if (date) {
            return moment(date).format(app_date_format);
        }

        return "";
    }

    function dateToSystemFormat(date) {
        if (date) {
            return moment(date, app_date_format).format(system_date_format);
        }

        return "";
    }


    function numberToCurrency(number, symbol) {
        if (number) {
            return parseFloat(number).toLocaleString('en-US', { maximumFractionDigits: 2 });
        }

        return "";
    }

    function generate(length) {

        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }


    function populateDropdownList(target, data, valueFieldOrCallback, displayFieldOrCallback, placeholder) {
        $(target).empty();


        if (typeof placeholder != 'undefined') {
            $(target).append($("<option>", {
                value: "",
                text: ""
            }));
        }
        let value = "";
        let display = "";
        data.forEach(function (item) {
            value = "";
            display = "";
            if (typeof valueFieldOrCallback === 'function') {
                value = valueFieldOrCallback(item);
            } else {
                value = item[valueFieldOrCallback];
            }

            if (typeof displayFieldOrCallback === 'function') {
                display = displayFieldOrCallback(item);
            } else {
                display = item[displayFieldOrCallback];
            }

            $(target).append($("<option>", {
                value: value,
                text: display
            }));
        });
        $(target).select2({
            placeholder: placeholder
        });

    }

    function populateCustomerDropdownList(url, target, callbackFunc) {
        $.post(url, {
            "ListCustomers": ""
        }, function (resp) {

            $(target).append($("<option>", {
                value: "",
                text: ""
            }));
            resp.forEach(function (item) {
                $(target).append($("<option>", {
                    value: item.CustomerID,
                    text: item.CustomerName
                }));
            });

            $(target).select2({
                placeholder: "Choose a customer"
            });

            if (typeof callbackFunc === 'function') {
                callbackFunc();
            }

        }, 'json');


    }

    function getInputValue(element, defaultValueIfNullOrEmpty = ""){
        let value = $(element).val();
        if (value == null || value == "") return defaultValueIfNullOrEmpty;
        
        return value;
    }
    
    function initializeFileInput(upload_url, fileId, displayFilename, inputGeneratedFilename, ImageName, PDFName) {

        $('#' + fileId).change(function (e) {

            var FileSize = e.target.files[0].size / 1024 / 1024;

            if (FileSize > 16) {
                ShowWarningMessage('You have exceeded the maximum file size limit of 16MB.');
                return;
            }

            var fileName = e.target.files[0].name;

            var ext = fileName.split('.').pop();

            $("#" + displayFilename).val(fileName);

            var NewFileName = "";
            var reader = new FileReader();

            if (ext == 'png' || ext == 'jpg' || ext == 'jpeg') {
                NewFileName = generate(16) + '.' + ext; // + '-' +  fileName;

                $(`#${inputGeneratedFilename}`).val(NewFileName);

                $("#" + ImageName).removeClass('hidden');



                reader.onload = function (e) {
                    document.getElementById(ImageName).src = e.target.result;
                };

                reader.readAsDataURL(this.files[0]);

                $('#' + PDFName).empty();
            } else if (ext == 'pdf') {
                NewFileName = generate(16) + '.' + ext; //+ '-' +  fileName.replace(' ','');
                $(`#${inputGeneratedFilename}`).val(NewFileName);

                $("#" + ImageName).addClass('hidden');

                reader.readAsDataURL(this.files[0]);

                $('#' + PDFName).empty();
                $('#' + PDFName).removeClass('hidden');

                var str = '';

                str += `<a target="_blank" href='${upload_url}${NewFileName}'>${NewFileName}</a>`;

                $('#' + PDFName).append(str);

            } else {
                ShowWarningMessage('File not supported.');
            }

        });
    }


    function resolveProductTypeName(productTypeID) {

        if (productTypeID == ProductTypes.Ashp) return 'Air Source Heat Pump';
        if (productTypeID == ProductTypes.Boiler) return 'Boiler';
        if (productTypeID == ProductTypes.ElectricCarCharger) return 'Electric Car Charger';
        if (productTypeID == ProductTypes.SolarPvBattery) return 'Solar PV + Battery';
        if (productTypeID == ProductTypes.SolarThermal) return 'Solar Thermal';
        if (productTypeID == ProductTypes.Battery) return 'Battery';

        return "Unresolve";
    }
    function InitializeSignature(signatureContainer, outputElement) {
        custsig = $(signatureContainer).signaturePad({
            drawOnly: true,
            lineTop: 155,
            output: outputElement,
            validateFields: true
        });

    }

    function getFilenameWithoutExtension(fullPath) {
        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
        var filename1 = fullPath.substring(startIndex);
        if (filename1.indexOf('\\') === 0 || filename1.indexOf('/') === 0) {
            filename1 = filename1.substring(1).replace(/^(.+?)(?:\.[^.]*)?$/, '$1');
        } else {
            filename1 = filename1.split('.').slice(0, -1).join('.');
        }

        return filename1;
    }
    function uploadImage(url, fileElement, fileKey, additionalData, callbackFunc) {
        var inputFile = $(fileElement).get(0);

        if (inputFile.files.length == 0) return;

        var file = inputFile.files[0];

        var form_data = new FormData();

        form_data.append(fileKey, file);

        if (additionalData) {
            for (let key in additionalData) {
                if (additionalData.hasOwnProperty(key)) {
                    form_data.append(key, additionalData[key]);
                }
            }
        }

        $.ajax({
            url: url, // point to server-side PHP script 
            dataType: 'json', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                if (typeof callbackFunc == 'function') {
                    callbackFunc(data);
                }
            }
        });
    }

    return {
        getInputValue: getInputValue,
        populateDropdownList: populateDropdownList,
        getFilenameWithoutExtension: getFilenameWithoutExtension,
        uploadImage: uploadImage,
        toAppDateFormat: dateToAppFormat,
        toSystemDateFormat: dateToSystemFormat,
        toCurrencyFormat: numberToCurrency,
        generateRandomString: generate,
        populateCustomerDropdownList: populateCustomerDropdownList,
        resolveProductTypeName: resolveProductTypeName,
        initializeFileInput: initializeFileInput,
        InitializeSignature: InitializeSignature,
        todayInAppFormat: function () {
            return moment().format(app_date_format);
        },
        todayInSystemFormat: function () {
            return moment().format(system_date_format);
        },
        getGeoLocation: function (callback) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
            else {
                ShowWarningMessage("Geolocation is not supported by this browser.");
                console.log("Geolocation is not supported by this browser.");
            }

            function showPosition(position) {
                if (typeof callback == "function") {
                    callback(position.coords.longitude, position.coords.latitude)
                }
            }
        },
        getIpAddress: function (callback) {
            $.get("https://ipinfo.io/json", function (response) {

                if (typeof callback == "function") {
                    callback(response.ip);
                }
            }, "jsonp");
        }
    };

})();

let SignatureHandler = (function () {
    let counter = 0;

    function create(container, label) {
        counter++;

        let _label = label;
        let _container = container;
        let _signatureContainerEl = `signature-container--${counter}`;
        let _signatureImgEl = `signature-container--${counter}`;
        let _signatureOuputEl = `signature-output--${counter}`;
        let _signatureDateContainerEl = `signature-date-container--${counter}`;
        let _signatureDateEl = `signature-date--${counter}`;
        let _printedNameEl = `signature-name--${counter}`;
        let _printedNameContainerEl = `signature-name-container--${counter}`;

        let html = `
        <div>
        <div class="${_signatureContainerEl}">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label" style="margin-top:7px;">${_label}</label>
                </div>
                <div class="col-md-6">
                    <ul class="sigNav hide-on-print-sb1">
                        <li class="clearButton" style="padding-right:20px"><a href="#clear">Clear</a></li>
                    </ul>
                </div>
            </div>
            <div class="sig sigWrapper">
                <div class="typed"></div>
    
                <img id="${_signatureImgEl}" class="hidden" src="" />
    
                <canvas class="pad" width=350 height="180"></canvas>
                <input type="hidden" id="${_signatureOuputEl}" name="${_signatureOuputEl}">
            </div>
        </div>
        <div class="form-group" style="display:none" id="${_printedNameContainerEl}">
            <label class="form-label" for="${_printedNameEl}">Printed Name</label>
            <input type="text" class="form-control" id="${_printedNameEl}" name="${_printedNameEl}" style="width:350px" disabled>
        </div>
        
    
        <div class="form-group mb-0 mt-2" style="display:none" id="${_signatureDateContainerEl}">
            <label class="control-label mb-0">Date</label>
            <input type="text" class="form-control" id="${_signatureDateEl}" name="${_signatureDateEl}" style="width:350px" disabled>
        </div>
    </div>
        `;
        $(_container).html(html);

        _app.InitializeSignature(`.${_signatureContainerEl}`, `#${_signatureOuputEl}`);

        return {
            setPrintedName: function (name) {
                $(`#${_printedNameContainerEl}`).show();
                $(`#${_printedNameEl}`).val(name);
            },
            setDateSign: function (date) {
                $(`#${_signatureDateContainerEl}`).show();
                $(`#${_signatureDateEl}`).val(date);
            },
            getSignature: function () {
                return $(`#${_signatureOuputEl}`).val();
            }
        }
    }
    return {
        create: create
    }

})();