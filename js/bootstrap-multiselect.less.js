/**
 * Bootstrap Multiselect (https://github.com/davidstutz/bootstrap-multiselect)
 *
 * Apache License, Version 2.0:
 * Copyright (c) 2012 - 2015 David Stutz
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a
 * copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 * BSD 3-Clause License:
 * Copyright (c) 2012 - 2015 David Stutz
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *    - Redistributions of source code must retain the above copyright notice,
 *      this list of conditions and the following disclaimer.
 *    - Redistributions in binary form must reproduce the above copyright notice,
 *      this list of conditions and the following disclaimer in the documentation
 *      and/or other materials provided with the distribution.
 *    - Neither the name of David Stutz nor the names of its contributors may be
 *      used to endorse or promote products derived from this software without
 *      specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
span.multiselect-native-select{
  position:relative
}

span.multiselect-native-select select{
  border :0 !important;
  clip: rect(0 0 0 0) !important;
  height: 1px !important;
  margin: -1px -1px -1px -3px !important;
  overflow: hidden !important;
  padding: 0 !important;
  position: absolute !important;
  width: 1px !important;
  left: 50%;
  top: 30px;
}

.multiselect-container {
  position: absolute;
  list-style-type: none;
  margin: 0;
  padding: 0;

  .input-group {
    margin: 5px;
  }

  > li {
    padding: 0;

    > a.multiselect-all label {
      font-weight: bold;
    }

    &.multiselect-group label {
      margin: 0;
      padding: 3px 20px 3px 20px;
      height: 100%;
      font-weight: bold;
    }

    &.multiselect-group-clickable label {
      cursor: pointer;
    }

    > a {
      padding: 0;

      > label {
        margin: 0;
        height: 100%;
        cursor: pointer;
        font-weight: normal;
        padding: 3px 20px 3px 40px;

        &.radio, &.checkbox {
          margin: 0;
        }

        > input[type="checkbox"] {
          margin-bottom:5px;
        }
      }
    }
  }
}

.btn-group > .btn-group:nth-child(2) > .multiselect.btn {
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
}

.form-inline .multiselect-container{

  label.checkbox, label.radio{
    padding: 3px 20px 3px 40px;
  }

  li a label{

    &.checkbox input[type="checkbox"], &.radio input[type="radio"]{
      margin-left: -20px;
      margin-right: 0;
    }
  }
}
