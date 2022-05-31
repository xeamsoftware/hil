<?php

use Illuminate\Database\Seeder;

class DesignationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("
            INSERT INTO `designations` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'DY. MGR.(ELECT)', '1', NULL, NULL),
(2, 'Opr Gr III ', '1', NULL, NULL),
(3, 'Sr. Assistant', '1', NULL, NULL),
(4, 'Sr.Asstt', '1', NULL, NULL),
(5, 'Asstt.', '1', NULL, NULL),
(6, 'Asstt', '1', NULL, NULL),
(7, 'ASSTT.MGR.(QA)', '1', NULL, NULL),
(8, 'QAO', '1', NULL, NULL),
(9, 'DY.FIN.MANAGER /\nDY. MGR. INTERNAL AUDI', '1', NULL, NULL),
(10, 'DY. MKTG. MNGR.', '1', NULL, NULL),
(11, 'Dy. Chief Medical Officer', '1', NULL, NULL),
(12, 'WELFARE OFFICER', '1', NULL, NULL),
(13, 'DM HR', '1', NULL, NULL),
(14, 'AM(M)', '1', NULL, NULL),
(15, 'Dy. Mgr.(Safety)', '1', NULL, NULL),
(16, 'Accountant Spl.Gr.', '1', NULL, NULL),
(17, 'AM( P)', '1', NULL, NULL),
(18, 'Fitter Gr.II', '1', NULL, NULL),
(19, 'Officer Hindi', '1', NULL, NULL),
(20, 'Sr. Assistant (spl Gr)', '1', NULL, NULL),
(21, 'Sr.Asstt (Spl Gr.)', '1', NULL, NULL),
(22, 'Tele. Opr/Recpt ', '1', NULL, NULL),
(23, 'Canner', '1', NULL, NULL),
(24, 'Asstt.(TK)', '1', NULL, NULL),
(25, 'DRIVER', '1', NULL, NULL),
(26, 'Opr. Gr III', '1', NULL, NULL),
(27, 'Fixed Term contract', '1', NULL, NULL),
(28, 'DM ( HR & A)', '1', NULL, NULL),
(29, 'Accts.Officer', '1', NULL, NULL),
(30, 'ASSTT. FINANCE MANAGER', '1', NULL, NULL),
(31, 'OPR.GR.II ', '1', NULL, NULL),
(32, 'Opr Gr I (SPL)', '1', NULL, NULL),
(33, 'OPR.GR.I', '1', NULL, NULL),
(34, 'Electrician Gr.I', '1', NULL, NULL),
(35, 'Foreman Electrician ', '1', NULL, NULL),
(36, 'Electrician Gr.II', '1', NULL, NULL),
(37, 'Sr. Tel.Tech.GR.II', '1', NULL, NULL),
(38, 'Electrician Gr.III ', '1', NULL, NULL),
(39, 'Elect G III', '1', NULL, NULL),
(40, 'Supervisor Inst.', '1', NULL, NULL),
(41, 'Inst.Mech.Gr.II', '1', NULL, NULL),
(42, 'Intrument Mech Gr.I', '1', NULL, NULL),
(43, 'Inst.Mech.Gr.III', '1', NULL, NULL),
(44, 'Ist. Mec Gr III', '1', NULL, NULL),
(45, 'Opr Gr I', '1', NULL, NULL),
(46, 'Opr Gr I ', '1', NULL, NULL),
(47, 'SR.MALI', '1', NULL, NULL),
(48, 'Carpenter Gr.I', '1', NULL, NULL),
(49, 'Carpenter Gr.II', '1', NULL, NULL),
(50, 'Foreman Mason', '1', NULL, NULL),
(51, 'CARPENTER GR-III', '1', NULL, NULL),
(52, 'PLUMBER GR-III ', '1', NULL, NULL),
(53, 'Opr Gr II', '1', NULL, NULL),
(54, 'Engr.(Chem)', '1', NULL, NULL),
(55, 'Opr. GrI ( SPl)', '1', NULL, NULL),
(56, 'Supervisor \n(Boiler Opr).', '1', NULL, NULL),
(57, 'Supervisor (Production)', '1', NULL, NULL),
(58, 'Welder Gr.II', '1', NULL, NULL),
(59, 'Rigger Gr.II', '1', NULL, NULL),
(60, 'Officer Accounts', '1', NULL, NULL),
(61, 'Asst. Officer (Accounts)', '1', NULL, NULL),
(62, 'Asstt.Accts.Officer', '1', NULL, NULL),
(63, 'Accountant', '1', NULL, NULL),
(64, 'TR.JR.ASSTT.', '1', NULL, NULL),
(65, 'Foreman Fitter', '1', NULL, NULL),
(66, 'AM(INSTRN)', '1', NULL, NULL),
(67, 'Shift Prod.Supervisor', '1', NULL, NULL),
(68, 'Officer ( Technical )', '1', NULL, NULL),
(69, 'AM(P)', '1', NULL, NULL),
(70, 'Sr.Asstt ', '1', NULL, NULL),
(71, 'DM(MECH)', '1', NULL, NULL),
(72, 'DM\n(UTILITY)', '1', NULL, NULL),
(73, 'ASSTT.MGR.(MECH)', '1', NULL, NULL),
(74, 'ASSTY. MGR. (CIVIL) ', '1', NULL, NULL),
(75, 'ENGR(MECH)', '1', NULL, NULL),
(76, 'SR. DRIVER', '1', NULL, NULL),
(77, 'F.A.D.', '1', NULL, NULL),
(78, 'Sr. FAD', '1', NULL, NULL),
(79, 'Supervisor (Engg. Services)', '1', NULL, NULL),
(80, 'ENGR(ELECT)', '1', NULL, NULL),
(81, 'Machinist Gr.II', '1', NULL, NULL),
(82, 'Sr Asstt', '1', NULL, NULL),
(83, 'ASSTT.MGR. (PUR)', '1', NULL, NULL),
(84, 'Asstt Officer ( Stores)', '1', NULL, NULL),
(85, 'DM ( C)', '1', NULL, NULL),
(86, 'SR Analyst', '1', NULL, NULL),
(87, 'Analyst', '1', NULL, NULL),
(88, 'O.A. ', '1', NULL, NULL),
(89, 'DM ( M)', '1', NULL, NULL),
(90, 'Head Peon', '1', NULL, NULL),
(91, 'Manager(OMS)', '1', NULL, NULL),
(92, 'PRODN.MANAGER', '1', NULL, NULL),
(93, 'ENGINEERING MGR.', '1', NULL, NULL),
(94, 'EM ( E & I)', '1', NULL, NULL),
(95, 'Manager ( R  & D)', '1', NULL, NULL),
(96, 'MGR TECH.SERVICES', '1', NULL, NULL),
(97, 'MANAGER ( HR)', '1', NULL, NULL),
(98, 'DY. GEN. MANAGER (F & A)', '1', NULL, NULL),
(99, 'Fitter Gr.III', '1', NULL, NULL),
(100, 'UNIT HEAD', '1', NULL, NULL),
(101, 'Foreman Boiler Opr.', '1', NULL, NULL),
(102, 'Fitter Gr.I', '1', NULL, NULL),
(103, 'Boiler Attn.\ncum Fitter III', '1', NULL, NULL),
(104, 'Supervisor Boiler', '1', NULL, NULL),
(105, 'Boiler Opr.II', '1', NULL, NULL),
(106, 'AM ( M)', '1', NULL, NULL),
(107, 'Electrician Gr. I', '1', NULL, NULL),
(108, 'Electrician Gr. II ', '1', NULL, NULL),
(109, 'Electrician Gr. III', '1', NULL, NULL),
(110, 'Fitter Gr.-I', '1', NULL, NULL),
(111, 'Instrument Mechanic Gr.  I', '1', NULL, NULL),
(112, 'PAINTER-II', '1', NULL, NULL),
(113, 'Machinist Gr.  III ', '1', NULL, NULL),
(114, 'Compressor Operator Gr.  I ', '1', NULL, NULL),
(115, 'Fitter-cum- boiler Attendant Gr.  III ', '1', NULL, NULL),
(116, 'Instrument Mechanic Gr.  II ', '1', NULL, NULL),
(117, 'WALDER-II', '1', NULL, NULL),
(118, 'Fitter Gr.-II', '1', NULL, NULL),
(119, 'Instrument Mechanic Gr.  II', '1', NULL, NULL),
(120, 'Fitter Gr.-III', '1', NULL, NULL),
(121, 'FITTER CUM-WALDER-III', '1', NULL, NULL),
(122, 'Carpenter Gr.  II', '1', NULL, NULL),
(123, 'MASSON,Gr.-III', '1', NULL, NULL),
(124, 'MALI', '1', NULL, NULL),
(125, 'Operator.Gr.I(Spl)', '1', NULL, NULL),
(126, 'Operator.Gr.I', '1', NULL, NULL),
(127, 'Operatorr.Gr.I', '1', NULL, NULL),
(128, 'Operator Gr.I', '1', NULL, NULL),
(129, 'Operter.Gr.II', '1', NULL, NULL),
(130, 'Operator.Gr.II', '1', NULL, NULL),
(131, 'Operator Gr.II', '1', NULL, NULL),
(132, 'U.S.L', '1', NULL, NULL),
(133, 'Operator.Gr.III', '1', NULL, NULL),
(134, 'Operator Gr.III', '1', NULL, NULL),
(135, 'Godown Keeper', '1', NULL, NULL),
(136, 'Sr.Assistant', '1', NULL, NULL),
(137, 'Sr.Assistant (SPL)', '1', NULL, NULL),
(138, 'Chief Dispenser', '1', NULL, NULL),
(139, 'Sr. Assistant. ', '1', NULL, NULL),
(140, 'Head Dispenser', '1', NULL, NULL),
(141, 'First-Aid Dispansar', '1', NULL, NULL),
(142, 'Store Keeper', '1', NULL, NULL),
(143, 'Assistant', '1', NULL, NULL),
(144, 'Junior Assistant.', '1', NULL, NULL),
(145, 'Peon ', '1', NULL, NULL),
(146, 'Manager (Production)/Unit Head', '1', NULL, NULL),
(147, 'Finance Manager', '1', NULL, NULL),
(148, 'Dy.  Commercial Manager', '1', NULL, NULL),
(149, 'Dy. Production Manager', '1', NULL, NULL),
(150, 'Dy. Manager(Production) ', '1', NULL, NULL),
(151, 'Dy. Manaager (Q.A.)', '1', NULL, NULL),
(152, 'Assistant Manager (Q.A.)', '1', NULL, NULL),
(153, 'Qulity Control Officer', '1', NULL, NULL),
(154, 'Assistant Manager (Marketing)', '1', NULL, NULL),
(155, 'A.M.(H.R&Admn.)', '1', NULL, NULL),
(156, 'Dy. Manaager (Civil)', '1', NULL, NULL),
(157, 'Dy.Manger(Mechanical)', '1', NULL, NULL),
(158, 'Assistant Manager( Chemical) ', '1', NULL, NULL),
(159, 'A.A.O', '1', NULL, NULL),
(160, 'Engineer(Instrumentation)', '1', NULL, NULL),
(161, 'Assistant Manager (Electrical) ', '1', NULL, NULL),
(162, 'Assistnat Officer (Accounts)', '1', NULL, NULL),
(163, 'Officer (Accounts)', '1', NULL, NULL),
(164, 'Assistant Manager (Finance)', '1', NULL, NULL),
(165, 'Officer(HR&Admn.)', '1', NULL, NULL),
(166, 'Senior Assistant (Spl.Grade)', '1', NULL, NULL),
(167, 'Assistnat Officer (Admn.)', '1', NULL, NULL),
(168, 'Fitter Gr.I(Spl.Grade)', '1', NULL, NULL),
(169, 'Engneer (Mech) Fixed Tenure Basis', '1', NULL, NULL),
(170, 'Hindi Officer Fixed Tenure Basis', '1', NULL, NULL),
(171, 'Engineer(Electrical) Fixed Tenure Basis', '1', NULL, NULL),
(172, 'SPS', '1', NULL, NULL),
(173, 'LM - (O)', '1', NULL, NULL),
(174, 'MAINT(SUPERVISOR)', '1', NULL, NULL),
(175, 'PAINTER - GR I', '1', NULL, NULL),
(176, 'ELECTRICAL SUPERVISOR', '1', NULL, NULL),
(177, 'OPR - GR I', '1', NULL, NULL),
(178, 'SUPERVISOR(ENGG.SERVICES)', '1', NULL, NULL),
(179, 'ASST OFFICER PURCHASE', '1', NULL, NULL),
(180, 'ASST MANAGER(ADMINISTRATION)', '1', NULL, NULL),
(181, 'ASST OFFICER WELFARE', '1', NULL, NULL),
(182, 'LM - FITTER', '1', NULL, NULL),
(183, ' OFFICER(ACCOUNTS)', '1', NULL, NULL),
(184, 'ASSISTANT OFFICER (ADMN)', '1', NULL, NULL),
(185, 'ASSISTANT MANAGER (PRODUCTION)', '1', NULL, NULL),
(186, 'DEM', '1', NULL, NULL),
(187, ' PRODUCTION MANAGER ', '1', NULL, NULL),
(188, 'LM - BLR', '1', NULL, NULL),
(189, 'VIGILANCE OFFICER', '1', NULL, NULL),
(190, 'NURSING ATTD', '1', NULL, NULL),
(191, 'PS TO GM', '1', NULL, NULL),
(192, 'OFFICER(STORE)', '1', NULL, NULL),
(193, 'BOILER ATTD - GR I', '1', NULL, NULL),
(194, 'ASSISTANT OFFICER(HR&ADMN)', '1', NULL, NULL),
(195, 'QUALITY ASSURANCE OFFICER', '1', NULL, NULL),
(196, 'FITTER - GR I', '1', NULL, NULL),
(197, 'OPR - GR III', '1', NULL, NULL),
(198, 'ASST - SPL - GR', '1', NULL, NULL),
(199, 'ACCT - SPL - GR', '1', NULL, NULL),
(200, 'MTS (I/C)', '1', NULL, NULL),
(201, 'LM-(ELECTRICIAN)', '1', NULL, NULL),
(202, 'SR - ASST', '1', NULL, NULL),
(203, 'ASSISTANT MANAGER (HINDI)', '1', NULL, NULL),
(204, 'DY PRODUCTION MANAGER', '1', NULL, NULL),
(205, 'MANAGER (EDP)', '1', NULL, NULL),
(206, 'ACCT - SPL - GR', '1', NULL, NULL),
(207, 'HELPER', '1', NULL, NULL),
(208, 'DY.ENGG.MANAGER(MECHANICAL)', '1', NULL, NULL),
(209, 'ASST ENGG MGR (CIVIL)', '1', NULL, NULL),
(210, 'ASST ENGG MGR (I)', '1', NULL, NULL),
(211, 'ANALYST - GR I', '1', NULL, NULL),
(212, 'SUPERVISOR(BOILER)', '1', NULL, NULL),
(213, 'ASSISTANT PRODUCTION MANAGER', '1', NULL, NULL),
(214, 'DY.  PRODUCTION MANAGER', '1', NULL, NULL),
(215, 'ASSISTANT MANAGER(TECHNICAL SERVICE)', '1', NULL, NULL),
(216, 'ASSISTANT FINANCE MANAGER', '1', NULL, NULL),
(217, 'DY GENERAL MANAGER (HR & ADMN)', '1', NULL, NULL),
(218, ' ASSISTANT', '1', NULL, NULL),
(219, 'SPL GR ASST (ERP)', '1', NULL, NULL),
(220, 'DY.MANAGER (R&D)', '1', NULL, NULL),
(221, 'SR.DRIVER', '1', NULL, NULL),
(222, 'ASST ENG MGR(ELECTRICAL)', '1', NULL, NULL),
(223, 'FITTER GR. II', '1', NULL, NULL),
(224, 'ELECTRICIAN GR. II', '1', NULL, NULL),
(225, 'ACCT', '1', NULL, NULL),
(226, 'FITTER GR. III', '1', NULL, NULL),
(227, 'FIRST AID DISPENSER', '1', NULL, NULL),
(228, 'CHEMICAL ENGINEER', '1', NULL, NULL),
(229, 'JAMADER', '1', NULL, NULL),
(230, 'ASSISTANT COMMERCIAL MANAGER', '1', NULL, NULL),
(231, 'DY.MANAGER (QA)', '1', NULL, NULL),
(232, 'ENGINEERING MANAGER', '1', NULL, NULL),
(233, 'ASST MANAGER (PURCHASE)', '1', NULL, NULL)");
    }
}