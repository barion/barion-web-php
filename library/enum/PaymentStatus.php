<?php

/**
 * Copyright 2016 Barion Payment Inc. All Rights Reserved.
 * <p/>
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * <p/>
 * http://www.apache.org/licenses/LICENSE-2.0
 * <p/>
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Barion\Common;

interface PaymentStatus
{
  // 10
  const Prepared = "Prepared";
  // 20
  const Started = "Started";
  // 21
  const InProgress = "InProgress";
  // 22
  const Waiting = "Waiting";
  // 25
  const Reserved = "Reserved";
  // 26
  const Authorized = "Authorized";
  // 30
  const Canceled = "Canceled";
  // 40
  const Succeeded = "Succeeded";
  // 50
  const Failed = "Failed";
  // 60
  const PartiallySucceeded = "PartiallySucceeded";
  // 70
  const Expired = "Expired";
}
