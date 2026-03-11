# PGSO Property System Flowchart

This document was updated by checking the implemented Laravel workflow in the current system, then aligning the flow with your reference process for issuance, transfer, return and inspection, disposal, and inventory management.

End-user image version: [system-flowchart-end-user.svg](/public/system-flowchart-end-user.svg)

## Validation Summary

- Issuance classification in code matches the requested threshold flow:
  `PAR` for PPE at `50,000 and above`, `ICS-SPHV` for semi-expendable `5,000 to 49,999`, and `ICS-SPLV` for `1 to 4,999`.
- Inventory management already exists as a real module:
  inventory records can be created manually, searched, printed, viewed individually, and tracked by QR/token.
- Approval is the real trigger for workflow updates:
  cards, accountability, RegSPI entries, and inventory movement updates happen on approval, not on print.
- Printing changes status to `issued` and produces the available PDF outputs.
- Transfer exists as `PTR` for PPE and `ITR` for semi-expendable.
- Return is now a separate module:
  `PRS` is generated for PPE returns and `RRSP` for semi-expendable returns.
- Disposal is now connected to approved return records:
  disposal drafts are created from an approved `PRS` or `RRSP`, then generate disposal outputs such as `IIRUP`, `IIRUSP`, `RRSEP`, and `WMR`.
- The requested return-and-inspection trail is partially implemented in automation:
  return approval updates inventory custody and accountability, but the current code still does not create every downstream card entry shown in the manual flowcharts.

## Overall Process

```mermaid
flowchart TD
    A[User logs in] --> B[Dashboard]

    B --> C[Maintain master data]
    C --> C1[Offices]
    C --> C2[Accountable Officers]
    C --> C3[Fund Clusters]
    C --> C4[Items]
    C --> C5[Signatories]
    C --> C6[Users and White Label]

    B --> D[Inventory Management]
    D --> D1[Create inventory records with QR and inventory code]
    D --> D2[Search and filter inventory]
    D --> D3[Print inventory list and tags]
    D --> D4[Open inventory detail and movement history]
    D --> D5[Track item publicly by QR token]

    B --> E[Issuance]
    E --> F[Approvals]
    B --> G[Transfer]
    G --> F
    B --> H[Return and Inspection]
    H --> F
    B --> I[Disposal]
    I --> F

    F -->|Approved| J[Apply workflow updates]
    F -->|Returned| K[Status becomes returned for correction]

    J --> L[Update inventory, accountability, and document controls]
    L --> M[Print generated forms]
    M --> N[Status becomes issued]

    B --> O[Reports]
    O --> O1[PPE physical count]
    O --> O2[Semi-expendable physical count]
    O --> O3[Office and fund-cluster breakdown]
    O --> O4[RegSPI]
    O --> O5[Audit and print logs]
```

## Inventory Management Flow

```mermaid
flowchart TD
    A[Open Inventory module] --> B[Create inventory record]
    B --> C[Enter description, item, unit, unit cost, classification, property no, date acquired]
    C --> D[Optional tag details: model, serial, accountable name, inventory committee]
    D --> E[Enter quantity]
    E --> F[System creates one inventory item per quantity]
    F --> G[System assigns inventory code and QR token]
    G --> H[Status = in_stock]

    H --> I[Inventory can be searched for issuance]
    H --> J[Inventory can be printed]
    H --> K[Inventory can be tracked by QR]

    I --> L[Approved issuance updates item to issued]
    L --> M[Transfer updates current accountable officer]
    M --> N[Disposal updates item to disposed]
```

## Property Issuance Flow

```mermaid
flowchart TD
    A[Start issuance] --> B[Input data]
    B --> B1[Entity name]
    B --> B2[Office and accountable officer]
    B --> B3[Fund cluster and date]
    B --> B4[Reference no if any]
    B --> B5[Quantity, unit, description]
    B --> B6[Property no and date acquired]
    B --> B7[Unit cost, total cost, remarks]
    B --> C[Save draft]
    C --> D{Classification by unit cost}

    D -->|>= 50000| E[Document type = PAR]
    D -->|5000 to 49999| F[Document type = ICS-SPHV]
    D -->|1 to 4999| G[Document type = ICS-SPLV]

    E --> H[Submit for approval]
    F --> H
    G --> H

    H --> I{Approving official action}
    I -->|Return| J[Status = returned]
    I -->|Approve| K[Status = approved]

    K --> L[Workflow updater runs]

    L -->|PAR / PPE| M[Generate document controls and outputs]
    M --> M1[PAR]
    M --> M2[Property Card]
    M --> M3[Sticker or Tag]
    M --> M4[Create or update employee accountability]

    L -->|ICS / Semi-expendable| N[Generate document controls and outputs]
    N --> N1[ICS]
    N --> N2[SPLV or SPHV classification output]
    N --> N3[Semi-Expendable Property Card]
    N --> N4[RegSPI entry]
    N --> N5[Sticker or Tag]
    N --> N6[Create or update employee accountability]

    M1 --> O[Print approved documents]
    M2 --> O
    M3 --> O
    N1 --> O
    N2 --> O
    N3 --> O
    N4 --> O
    N5 --> O
    O --> P[Status = issued]
```

## Property Transfer Flow

```mermaid
flowchart TD
    A[Accountable officer starts transfer request] --> B[Select issued source item or issuance]
    B --> C[Input data]
    C --> C1[Entity name]
    C --> C2[From employee and to employee]
    C --> C3[Fund cluster and transfer date]
    C --> C4[Transfer type]
    C --> C5[Reference issuance number]
    C --> C6[Quantity, unit, description, amount, condition]
    C --> D{Source document type}

    D -->|PPE| E[Document type = PTR]
    D -->|Semi-expendable| F[Document type = ITR]

    E --> G[Save draft and submit]
    F --> G

    G --> H{Approving official action}
    H -->|Return| I[Status = returned]
    H -->|Approve| J[Status = approved]

    J --> K[Workflow updater runs]
    K --> L[Inventory holder changes to receiving employee]
    K --> M[Previous accountability marked transferred]
    K --> N[New accountability line created for receiving employee]
    K --> O[Generate PTR or ITR document control]
    K --> P[Generate sticker or tag control]

    O --> Q[Print PTR or ITR]
    P --> Q
    Q --> R[Status = issued]

    R --> S[Requested document trail]
    S --> S1[PPE trail: PTR -> PC -> IPC]
    S --> S2[Semi-expendable trail: ITR -> PCSEP -> IPCSEP]
```

## Return and Inspection Flow

```mermaid
flowchart TD
    A[Accountable officer initiates return] --> B[Input return and inspection data]
    B --> B1[Entity name]
    B --> B2[Accountable officer name, designation, office]
    B --> B3[Date and source PAR or ICS number]
    B --> B4[Item details, quantity, unit, description, property no]
    B --> B5[Unit value, total value, condition, reason, remarks]
    B --> C{Asset class}

    C -->|PPE| D[Create PRS draft]
    C -->|Semi-expendable| E[Create RRSP draft]

    D --> F[Submit for approval]
    E --> F

    F --> G{Approving official action}
    G -->|Return| H[Status = returned]
    G -->|Approve| I[Status = approved]

    I --> J[Inventory movement is recorded against the selected item]
    J --> K[Accountability line is updated]
    K --> L[Document controls become available for printing]

    L -->|PPE requested trail| M[PRS -> IIRUP -> PC -> IPC]
    L -->|Semi-expendable requested trail| N[RRSP -> IIRUSP or RRSEP -> PCSEP -> IPCSEP]

    M --> O[Print return and inspection documents]
    N --> O
    O --> P[Status = issued]
```

## Property Disposal Flow

```mermaid
flowchart TD
    A[Start disposal process] --> B[Use returned or issued item as source]
    B --> C[Input data]
    C --> C1[Entity name and accountable officer]
    C --> C2[Designation, station, fund cluster]
    C --> C3[Date acquired and disposal date]
    C --> C4[Particulars, property no, quantity]
    C --> C5[Unit cost, total cost, depreciation, carrying amount]
    C --> C6[Action, appraised value, OR no, sale amount, remarks]
    C --> D{Asset class}

    D -->|PPE| E[Primary disposal path uses PRS and can proceed to IIRUP]
    D -->|Semi-expendable| F[Primary disposal path uses RRSP and can proceed to IIRUSP or RRSEP]

    E --> G[Submit for approval]
    F --> G

    G --> H{Approving official action}
    H -->|Return| I[Status = returned]
    H -->|Approve| J[Status = approved]

    J --> K[Workflow updater marks inventory item as disposed]
    J --> L[Source accountability lines become disposed]
    J --> M[Document controls are generated]

    M -->|PPE| N[Available outputs: PRS, IIRUP, WMR]
    M -->|Semi-expendable| O[Available outputs: RRSP, IIRUSP, RRSEP, WMR]

    N --> P[Print disposal documents]
    O --> P
    P --> Q[Status = issued]
```

## Status Lifecycle

```mermaid
stateDiagram-v2
    [*] --> Draft
    Draft --> Submitted: Submit for approval
    Submitted --> Approved: Approval approved
    Submitted --> Returned: Approval returned
    Approved --> Issued: Print document
    Returned --> Draft: User revises and resubmits
    Issued --> [*]
```
