<?php

namespace App\Constants;

class Organization
{
    public const DEPT_DIVISIONS = [
        'OFFICE GENERAL MANAGER DEPARTMENT (OGM)' => 
        [
        ],
        'CORPORATE PLANNING DEPARTMENT (CORPLAN)' => 
        [
            0 => 'CORPLAN DIVISION',
            1 => 'I.T. DIVISION',
            2 => 'CORPORATE PLANNING DIVISION',
        ],
        'INSTITUTIONAL SERVICE DEPARTMENT (ISD)' => 
        [
            0 => 'HUMAN RESOURCE DIVISION',
            1 => 'MANAGEMENT SERVICE DIVISION',
        ],
        'FINANCE DEPARTMENT (FIN DEPT)' => 
        [
            0 => 'ACCOUNTING DIVISION',
            1 => 'BUDGET & TREASURER DIVISION',
        ],
        'AUDIT DEPARTMENT (AUDIT DEPT)' => 
        [
            0 => 'FINANCIAL DIVISION',
            1 => 'TECHNICAL DIVISION',
        ],
        'ENGINEERING DEPARTMENT (ENGR DEPT)' => 
        [
            0 => 'SYSTEMS LOSS REDUCTION DIVISION',
            1 => 'PLANNING DIVISION',
            2 => 'CONSTRUCTION MANAGE & SERVICING DIVISION',
        ],
        'NAGA AREA OFFICE (NAO)' => 
        [
        ],
        'NORTH AREA OFFICE' => 
        [
            0 => 'CANAMAN MAGARAO SUB-OFFICE (CMSO)',
            1 => 'CALABANGA BOMBON SUB-OFFICE (CBSO)',
            2 => 'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
        ],
        'SOUTH AREA OFFICE' => 
        [
            0 => 'PILI SUB-OFFICE (PSO)',
            1 => 'MILAOR MINALABAC SUB-OFFICE (MMSO)',
        ],
        'DEPARTMENT CHECK' => 
        [
            0 => 'DIVISION CHECK',
        ],
    ];

    public const DEPARTMENTS = [
        0 => 'OFFICE GENERAL MANAGER DEPARTMENT (OGM)',
        1 => 'CORPORATE PLANNING DEPARTMENT (CORPLAN)',
        2 => 'INSTITUTIONAL SERVICE DEPARTMENT (ISD)',
        3 => 'FINANCE DEPARTMENT (FIN DEPT)',
        4 => 'AUDIT DEPARTMENT (AUDIT DEPT)',
        5 => 'ENGINEERING DEPARTMENT (ENGR DEPT)',
        6 => 'NAGA AREA OFFICE (NAO)',
        7 => 'NORTH AREA OFFICE',
        8 => 'SOUTH AREA OFFICE',
        9 => 'DEPARTMENT CHECK',
    ];

    public const LOCATIONS = [
        0 => 'MAIN OFFICE (MAIN)',
        1 => 'NAGA AREA OFFICE (NAO)',
        2 => 'PILI SUB-OFFICE (PSO)',
        3 => 'MILAOR MINALABAC SUB-OFFICE (MMSO)',
        4 => 'CANAMAN MAGARAO SUB-OFFICE(CMSO)',
        5 => 'CALABANGA BOMBON SUB-OFFICE (CBSO)',
        6 => 'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
        7 => 'LOCATION CHECK (EDIT CHECK)',
    ];

    public const DIVISIONS = [
        0 => 'CORPLAN DIVISION',
        1 => 'I.T. DIVISION',
        2 => 'CORPORATE PLANNING DIVISION',
        3 => 'HUMAN RESOURCE DIVISION',
        4 => 'MANAGEMENT SERVICE DIVISION',
        5 => 'ACCOUNTING DIVISION',
        6 => 'BUDGET & TREASURER DIVISION',
        7 => 'FINANCIAL DIVISION',
        8 => 'TECHNICAL DIVISION',
        9 => 'SYSTEMS LOSS REDUCTION DIVISION',
        10 => 'PLANNING DIVISION',
        11 => 'CONSTRUCTION MANAGE & SERVICING DIVISION',
        12 => 'CANAMAN MAGARAO SUB-OFFICE (CMSO)',
        13 => 'CALABANGA BOMBON SUB-OFFICE (CBSO)',
        14 => 'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
        15 => 'PILI SUB-OFFICE (PSO)',
        16 => 'MILAOR MINALABAC SUB-OFFICE (MMSO)',
        17 => 'DIVISION CHECK',
    ];
}
